<?php namespace App\Repositories;

use App\Billing\Stripe;
use App\Models\User;
use App\Repositories\BaseRepository;
use File;

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
}