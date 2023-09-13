<?php namespace App\Repositories;

use App\Billing\Stripe;
use App\Exceptions\GeneralException;
use App\Models\User;
use App\Repositories\BaseRepository;
use File;
use Stripe\Source;
use Stripe\Token;

/**
 * Class UserRepository.
*/
class UserRepository extends BaseRepository
{
    /**
     * Associated Repository Model.
     */
    const MODEL = User::class;

    /**
     * Method update
     *
     * @param array $data [explicite description]
     * @param \App\Models\User $user [explicite description]
     *
     * @return bool
     */
    public function update(array $data, User $user): bool
    {
        // upload profile image
        $this->upload($data, $user);

        return $user->update($data);
    }

    /**
     * Method upload
     *
     * @param array $data [explicite description]
     * @param User $user [explicite description]
     *
     * @return void
     */
    private function upload(array $data, User $user)
    {
        if(isset($data['profile_image']))
        {
            $image = $data['profile_image'];
            // original file name
            $originalFileName = $image->getClientOriginalName();
            $fileArr['original_name'] = $originalFileName;

            $fileNameStr = pathinfo($originalFileName, PATHINFO_FILENAME);

            // Replaces all spaces with hyphens.
            $fileNameStr = str_replace(' ', '-', $fileNameStr);
            // Remove special chars.
            $fileNameStr = preg_replace('/[^A-Za-z0-9\-\_]/', '', $fileNameStr);

            $path        = public_path('storage/profile');
            $counter     = 1;

            // remove old file
            if (File::exists($user->image)){
                File::delete($user->image);
            }
            // check file already exist in the folder
            if( file_exists($path.$fileNameStr. '.' . $image->getClientOriginalExtension()) )
            {
                $exists = true;
                while( $exists )
                {
                    $increment = $fileNameStr.$counter. '.' . $image->getClientOriginalExtension();
                    if( file_exists($path.$increment) )
                    {
                        $counter++;
                    }
                    else
                    {
                        $exists = false;
                    }
                }
                $fileNameStr = $fileNameStr.$counter. '.' . $image->getClientOriginalExtension();
            }
            else
            {
                $fileNameStr = $fileNameStr. '.' . $image->getClientOriginalExtension();
            }
            $image->move($path, $fileNameStr);

            $user->attachment()->delete();
            $user->attachment()->create([
                'original_name' => $fileNameStr,
                'stored_name'   => $fileNameStr
            ]);
        }
    }

    /**
     * Method addFavourite
     *
     * @param array $data [explicite description]
     * @param \App\Models\User $user [explicite description]
     *
     * @return void
     */
    public function addFavourite(array $data, User $user) : void
    {
        $restaurantItemId = isset($data['restaurant_item_id']) ? $data['restaurant_item_id'] : null;

        // check whether favourite item exist or not
        if( $this->checkFavouriteItemExist($restaurantItemId, $user) )
        {
            $user->favourite_items()->detach([$restaurantItemId]);
        }
        else
        {
            $user->favourite_items()->attach([$restaurantItemId]);
        }
    }

    /**
     * Method checkFavouriteItemExist
     *
     * @param int $restaurantItemId [explicite description]
     * @param \App\Models\User $user [explicite description]
     *
     * @return int
     */
    public function checkFavouriteItemExist(int $restaurantItemId, User $user): int
    {
        return $user->favourite_items()->where('user_favourite_items.restaurant_item_id', $restaurantItemId)->count();
    }

    /**
     * Method create
     *
     * @param array $data [explicite description]
     *
     * @return \App\Models\User
     */
    public function create(array $data): User
    {
        if( isset( $data['user_type'] ) )
        {
            $user = User::create($data);
            if( $data['user_type'] == User::CUSTOMER )
            {
                $stripe     = new Stripe();
                $customer   = $stripe->createCustomer($data);
                $str['stripe_customer_id'] = $customer->id;
                $user->update($str);
                $user->payment_methods()->create([
                    'name' => 'Cash'
                ]);
            }
        }
        return $user;
    }

    /**
     * Method fetchCard
     *
     * @param array $data [explicite description]
     *
     * @return array
     */
    function fetchCard(array $data) : array
    {
        $customer_id    = isset($data['customer_id']) ? $data['customer_id'] : null;
        $stripe         = new Stripe();
        $customer_cards = $stripe->fetchCards($customer_id);
        $customer       = $stripe->fetchCustomer($customer_id);
        $cards          = [];

        foreach ($customer_cards->data as $value) {
            $cards[] = [
                'id'            => $value->id,
                'name'          => $value->name,
                'fingerprint'   => $value->fingerprint,
                'brand'         => $value->brand,
                'country'       => $value->country,
                'exp_month'     => $value->exp_month,
                'exp_year'      => $value->exp_year,
                'last4'         => $value->last4,
                'default_card'  => ($customer->default_source == $value->id) ?? false ,
            ];
        }

        return $cards;
    }

    /**
     * Method deleteUserCard
     *
     * @param array $data [explicite description]
     *
     * @return mixed
     */
    public function deleteUserCard(array $data)
    {
        $user           = auth()->user();
        $card_id        = isset($data['card_id']) ? $data['card_id'] : null;
        $stripe         = new Stripe();
        $delete         = $stripe->deleteCard($user->stripe_customer_id , $card_id);

        return $delete->deleted;
    }

    /**
     * Method retrieveToken
     *
     * @param string $token [explicite description]
     *
     * @return Token
     */
    public function retrieveToken(string $token): Token
    {
        $stripe         = new Stripe();
        $token          = isset( $token ) ? $token : null;
        return $stripe->retrieveToken($token);
    }

    public function attachCard(array $data)
    {
        $user           = auth()->user();
        $token          = isset( $data['token'] ) ? $this->retrieveToken($data['token']) : null;
        $fingerprint    = $token->card->fingerprint;
        $cards          = $this->fetchCard(['customer_id' => $user->stripe_customer_id]);
        $stripe         = new Stripe();

        // check card exist
        if( !$this->checkCardAlreadyExist($cards, $fingerprint) )
        {
            // generate source
            $source = $this->generateSource($stripe, $user, $token);

            // attach source to customer
            return $source = $this->attachSource($stripe, $user->stripe_customer_id, $source);
        }
        else
        {
            throw new GeneralException('Card is already taken for this customer.');
        }
    }

    /**
     * Method checkCardAlreadyExist
     *
     * @param array $card [explicite description]
     * @param string $fingerprint [explicite description]
     *
     * @return bool
     */
    public function checkCardAlreadyExist(array $card, string $fingerprint): bool
    {
        $exist          = false;
        if( !empty( $cards ) )
        {
            foreach( $cards as $card )
            {
                if( $card['fingerprint'] === $fingerprint )
                {
                    $exist = true;
                }
                else
                {
                    continue;
                }
            }
        }

        return $exist;
    }

    /**
     * Method generateSource
     *
     * @param Stripe $stripe [explicite description]
     * @param User $user [explicite description]
     * @param Token $token [explicite description]
     *
     * @return Source
     */
    private function generateSource(Stripe $stripe, User $user, Token $token): Source
    {
        // generate source
        return $stripe->createSource($user->email, $token);
    }

    /**
     * Method attachSource
     *
     * @param Stripe $stripe [explicite description]
     * @param string $customerId [explicite description]
     * @param Source $source [explicite description]
     *
     * @return mixed
     */
    private function attachSource(Stripe $stripe, string $customerId, Source $source)
    {
        return $stripe->attachSource($customerId, $source->id);
    }
}