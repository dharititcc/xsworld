<?php namespace App\Repositories;

use App\Billing\Stripe;
use App\Events\GiftCardEvent;
use App\Events\RegisterEvent;
use App\Exceptions\GeneralException;
use App\Mail\PurchaseGiftCard;
use App\Models\User;
use App\Models\UserDevices;
use App\Models\UserGiftCard;
use App\Models\UsersVerifyMobile;
use App\Repositories\BaseRepository;
use File;
use Illuminate\Foundation\Mix;
use Illuminate\Support\Facades\Mail;
use Stripe\Source;
use Stripe\Token;
use Illuminate\Support\Facades\URL;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

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
                // verification email send and send verification code
                event(new RegisterEvent($user));
                $qr_url = URL::current();
                $qr_code_image = QrCode::size(500)
                    ->format('png')
                    ->backgroundColor(139,149,255,0)
                    ->generate($qr_url . '/'.$user->id, public_path("customer_qr/qrcode_$user->id.png"));
                
                $imageName = "qrcode_$user->id.png";
                User::where('id',$user->id)->update(['cus_qr_code_img' => $imageName]);

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
     * Method Socialcreate
     *
     * @param array $data [explicite description]
     *
     * @return \App\Models\User
     */
    public function Socialcreate(array $data) : User
    {
        $user = User::where('email', $data['email'])->first();
        if(!$user){
            $user = User::create($data);
            if( $data['user_type'] == User::CUSTOMER )
            {
                $stripe     = new Stripe();
                $customer   = $stripe->createCustomer($data);
                $str['stripe_customer_id'] = $customer->id;
                $user->update($str);
            }
        }
        if( $user instanceof \App\Models\User && isset( $user->id ) )
        {
            auth()->login($user);
            return $user;
        }
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
        $user           = auth()->user();
        $stripe         = new Stripe();
        $customer_cards = $stripe->fetchCards($user->stripe_customer_id);
        $customer       = $stripe->fetchCustomer($user->stripe_customer_id);
        $cards          = [];
        // dd($customer_cards);
        foreach ($customer_cards->data as $value) {
            $cards[] = [
                'id'            => $value->id,
                'name'          => $value->name ?? '',
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
            return $source = $this->attachSource($stripe, $user->stripe_customer_id, $token);
        }
        else
        {
            throw new GeneralException('Card is already taken for this customer.');
        }
    }

    /**
     * Method checkCardAlreadyExist
     *
     * @param array $cards [explicite description]
     * @param string $fingerprint [explicite description]
     *
     * @return bool
     */
    public function checkCardAlreadyExist(array $cards, string $fingerprint): bool
    {
        $exist          = false;

        if( !empty( $cards ) )
        {
            foreach( $cards as $card )
            {
                if( $card['fingerprint'] == $fingerprint )
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
     * @param Token $source [explicite description]
     *
     * @return \Stripe\Account|\Stripe\BankAccount|\Stripe\Card|\Stripe\Source
     */
    private function attachSource(Stripe $stripe, string $customerId, Token $source)
    {
        return $stripe->attachSource($customerId, $source->id);
    }

    /**
     * Method markDefaultCard
     *
     * @param array $input [explicite description]
     *
     * @return bool
     * @throws \App\Exceptions\GeneralException
     */
    public function markDefaultCard(array $input): bool
    {
        if( isset($input['card_id']) )
        {
            $user           = auth()->user();
            $stripe         = new Stripe();
            $customer       = $stripe->fetchCustomer($user->stripe_customer_id);

            $customer->default_source = $input['card_id'];

            if($customer->save())
            {
                return true;
            }

            throw new GeneralException('Card is failed to mark as default.');
        }

        throw new GeneralException('Card id is required.');
    }

    /**
     * Method storeDevice
     *
     * @param User $user [explicite description]
     * @param array $input [explicite description]
     *
     * @return \App\Models\UserDevices
     *
     * @throws \App\Exceptions\GeneralException
     */
    public function storeDevice(User $user, array $input): UserDevices
    {
        if( isset($input['fcm_token']) )
        {
            // inser device entry
            return $user->devices()->create(['fcm_token' => $input['fcm_token']]);
        }

        throw new GeneralException('Failed to store device.');
    }

    /**
     * Method sendOtp
     *
     * @param array $input [explicite description]
     *
     * @return void
     *
     * @throws \App\Exceptions\GeneralException
     */
    public function sendOtp(array $input)
    {
        $user = User::where(['country_code' => $input['country_code'], 'phone' => $input['mobile_no'] ])->first();

        if(isset($user->id))
        {
            $n        = 6;
            $otp      = generateNumericOTP($n);
            $user_sms = UsersVerifyMobile::where('otp',$otp)->first();
            if($user_sms)
            {
                $otp  = generateNumericOTP($n);
            }

            $data['user_id']        = $user->id;
            $data['country_code']   = $input['country_code'];
            $data['mobile_no']      = $input['mobile_no'];
            $data['otp']            = $otp;

            $saveotp    = UsersVerifyMobile::create($data);
            $mobile_no  = $input['country_code'].$input['mobile_no'];

            // Send OTP to User
            $send_otp   = sendTwilioCustomerSms($mobile_no,$otp);
            return $data['user_id'];
        }

        throw new GeneralException('User not found.');
    }

    /**
     * Method resendLink
     *
     * @param array $input [explicite description]
     *
     * @return User
     */
    public function resendLink(array $input) : User
    {
        $user = User::where(['email' => $input['email'] ,'user_type' => User::CUSTOMER ])->first();
        if(isset($user->id))
        {
            event(new RegisterEvent($user));
            return $user;
        }
        throw new GeneralException('User not found.');
    }

    /**
     * Method VerifyOtp
     *
     * @param array $input [explicite description]
     *
     * @return mixed
     */
    public function VerifyOtp(array $input) : mixed
    {
        $user = auth()->user();
        $users = UsersVerifyMobile::with('user')->where(['user_id' => $user->id ,'otp' => $input['otp'] ])->first();

        if(isset($users->id))
        {
            $str['is_mobile_verify'] = 1;
            $user->update($str);
            return $users;
        }
        throw new GeneralException('OTP is invalid.');

    }

    /**
     * Method purchaseGiftCard
     *
     * @param array $data [explicite description]
     *
     * @return mixed
     */
    public function purchaseGiftCard(array $data) : mixed
    {
        $user = auth()->user();

        $code = $this->generateRandomString(10);

        if($user->stripe_customer_id != '')
        {
            $stripe         = new Stripe();
            $customer       = $stripe->fetchCustomer($user->stripe_customer_id);
            $default_card   = $customer->default_source;
            $amount         = floatval(number_format($data['amount'],2));

            if($default_card)
            {
                $paymentArr = [
                    'amount'        => $amount  * 100,
                    'currency'      => 'AUD',
                    'customer'      => $user->stripe_customer_id,
                    'source'        => $default_card,
                    'description'   => 'Gift Card Purchase '. $data['name']
                ];

                $payment_data   = $stripe->createCharge($paymentArr);

                $giftcardArr = [
                    'user_id'           => $user->id,
                    'name'              => $data['name'],
                    'from_user'         => $user->email,
                    'to_user'           => $data['to_user'],
                    'amount'            => $data['amount'],
                    'code'              => $code,
                    'status'            => UserGiftCard::PENDING,
                    'transaction_id'    => $payment_data->balance_transaction
                ];

                $savegiftcard   = UserGiftCard::create($giftcardArr);
                event(new GiftCardEvent($savegiftcard));

                return $savegiftcard;
            }
            throw new GeneralException('Please add Card');
        }

        throw new GeneralException('Please select default card');

    }

    /**
     * Method generateRandomString
     *
     * @param $length $length [explicite description]
     *
     * @return void
     */
    function generateRandomString($length = 10)
    {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = substr(str_shuffle($characters), 0, $length);
        return $randomString;
    }

    /**
     * Method redeemGiftCard
     *
     * @param array $input [explicite description]
     *
     * @return mixed
     */
    function redeemGiftCard(array $input)
    {
        if( isset($input['code']) )
        {
            $user = auth()->user();
            $redeem = UserGiftCard::where(['code' => $input['code'] , 'status' => UserGiftCard::PENDING ])->orderBy('id', 'desc')->first();

            if($redeem)
            {
                if($user->email != $redeem->from_user)
                {
                    $data['status']         = UserGiftCard::REDEEMED;
                    $data['verify_user_id'] = $user->id;
                    $redeem->update($data);

                    $users['credit_amount'] = $user->credit_amount + $redeem->amount;
                    $user->update($users);

                    return $user;
                }
                else
                {
                    throw new GeneralException('You are not redeem your own gift card.');
                }
            }
            throw new GeneralException('Invalid Redeem code.');

        }

        throw new GeneralException('Redeem Code is required.');
    }
}