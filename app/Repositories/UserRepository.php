<?php namespace App\Repositories;

use App\Billing\Stripe;
use App\Events\GiftCardEvent;
use App\Events\RegisterEvent;
use App\Exceptions\GeneralException;
use App\Mail\PurchaseGiftCard;
use App\Models\Country;
use App\Models\CustomerTable;
use App\Models\FriendRequest;
use App\Models\User;
use App\Models\UserDevices;
use App\Models\UserGiftCard;
use App\Models\UserOtps;
use App\Models\UserReferrals;
use App\Models\UsersVerifyMobile;
use App\Repositories\BaseRepository;
use App\Repositories\Traits\SpinWheel;
use File;
use Illuminate\Foundation\Mix;
use Illuminate\Http\Request;
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
    use SpinWheel;

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

        if( $user->first_name == '' && $user->email == '' && $user->birth_date == '' )
        {
            // insert credit point histories table
            $arrCreditPoint = [
                'model_name' => '\App\Models\User',
                'model_id'   => $user->id,
                'points'     => User::SIGN_UP_POINTS,
                'type'       => 1
            ];

            $this->insertCreditPoints($user, $arrCreditPoint);

            $data['points'] = User::SIGN_UP_POINTS;
        }

        if( access()->isCustomer() && !isset($user->stripe_customer_id) )
        {
            // create stripe customer
            $stripeCustomerData         = [
                'name' => $user->full_name,
                'phone'=> $user->phone,
                'email'=> $data['email']
            ];
            $stripe                     = new Stripe();
            $customer                   = $stripe->createCustomer($stripeCustomerData);
            $data['stripe_customer_id'] = $customer->id;
        }

        $updateSuccess = $user->update($data);

        return $updateSuccess;
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
            if(isset($data['referral_code']))
            {
                $refer_user             = User::where('referral_code',$data['referral_code'])->first();

                if(isset($refer_user->id))
                {
                    $data['referral_code']  = referralCode();
                    $data['referrer_id']    = $refer_user->id;
                    $data['points']         = $data['points'] + UserReferrals::TO_USER_POINTS;

                    $refer_user['points']   = $refer_user->points + UserReferrals::FROM_USER_POINTS;
                    $refer_user->update();

                    // insert credit point histories table

                    $arrCreditPoint = [
                        'model_name' => '\App\Models\User',
                        'model_id'   => $refer_user->id,
                        'points'     => UserReferrals::FROM_USER_POINTS,
                        'type'       => 1
                    ];

                    $this->insertCreditPoints($refer_user, $arrCreditPoint);

                    $referred_data['from_user_id']                  = $refer_user->id;
                    $referred_data['points_earned_by_to_user']      = UserReferrals::FROM_USER_POINTS;
                    $referred_data['points_earned_by_from_user']    = UserReferrals::TO_USER_POINTS;
                    $referred_data['status']                        = UserReferrals::ACCEPTED;

                    $referred_user     = UserReferrals::create($referred_data);
                }
            }

            $user = User::create($data);
            if( isset($data['fcm_token']) )
            {
                // inser device entry
                $user->devices()->create(['fcm_token' => $data['fcm_token']]);
            }
            if( $data['user_type'] == User::CUSTOMER )
            {
                //Create Folder & give permission
                $path = storage_path("app/public/customer_qr");
                !is_dir($path) &&
                    mkdir($path, 0777, true);
                // verification email send and send verification code
                event(new RegisterEvent($user));
                $qr_url = URL::current();
                $qr_code_image = QrCode::size(500)
                    ->format('png')
                    ->backgroundColor(139,149,255,0)
                    ->generate($qr_url . '?user_id='.$user->id, public_path("storage/customer_qr/qrcode_$user->id.png"));


                $imageName = "qrcode_$user->id.png";
                User::where('id',$user->id)->update(['cus_qr_code_img' => $imageName]);
                $user->cus_qr_code_img = $imageName;

                $user['referral_code']     = referralCode();
                $stripe                    = new Stripe();
                $customer                  = $stripe->createCustomer($data);
                $str['stripe_customer_id'] = $customer->id;
                $user->update($str);
                $user->payment_methods()->create([
                    'name' => 'Cash'
                ]);

                // insert credit point histories table

                $arrCreditPoint = [
                    'model_name' => '\App\Models\User',
                    'model_id'   => $user->id,
                    'points'     => User::SIGN_UP_POINTS,
                    'type'       => 1
                ];

                $this->insertCreditPoints($user, $arrCreditPoint);

                // Latest user id for to_user_id in User referrals
                if(isset($referred_user->id))
                {
                    // insert credit point histories table

                    $arrCreditPoint = [
                        'model_name' => '\App\Models\User',
                        'model_id'   => $user->id,
                        'points'     => UserReferrals::TO_USER_POINTS,
                        'type'       => 1
                    ];

                    $this->insertCreditPoints($user, $arrCreditPoint);

                    $referred_id['to_user_id']  = $user->id;
                    $referred_user->update($referred_id);
                }
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

            if(isset($data['referral_code']))
            {
                $refer_user             = User::where('referral_code',$data['referral_code'])->first();
                $data['referral_code']  = referralCode();
                $data['referrer_id']    = $refer_user->id;
                $data['points']         = $data['points'] + UserReferrals::TO_USER_POINTS;

                $refer_user['points']   = $refer_user->points + UserReferrals::FROM_USER_POINTS;
                $refer_user->update();

                // insert credit point histories table

                $arrCreditPoint = [
                    'model_name' => '\App\Models\User',
                    'model_id'   => $refer_user->id,
                    'points'     => UserReferrals::FROM_USER_POINTS,
                    'type'       => 1
                ];

                $this->insertCreditPoints($refer_user, $arrCreditPoint);

                $referred_data['from_user_id']                  = $refer_user->id;
                $referred_data['points_earned_by_to_user']      = UserReferrals::FROM_USER_POINTS;
                $referred_data['points_earned_by_from_user']    = UserReferrals::TO_USER_POINTS;
                $referred_data['status']                        = UserReferrals::ACCEPTED;

                $referred_user     = UserReferrals::create($referred_data);
            }

            $user = User::create($data);
            if( isset($data['fcm_token']) )
            {
                // inser device entry
                $user->devices()->create(['fcm_token' => $data['fcm_token']]);
            }
            if( $data['user_type'] == User::CUSTOMER )
            {
                //Create Folder & give permission
                $path = storage_path("app/public/customer_qr");
                !is_dir($path) &&
                    mkdir($path, 0777, true);
                $qr_url = URL::current();
                $qr_code_image = QrCode::size(500)
                    ->format('png')
                    ->backgroundColor(139,149,255,0)
                    ->generate($qr_url . '?user_id='.$user->id, public_path("storage/customer_qr/qrcode_$user->id.png"));


                $imageName = "qrcode_$user->id.png";
                User::where('id',$user->id)->update(['cus_qr_code_img' => $imageName]);
                $user->cus_qr_code_img = $imageName;

                $stripe                     = new Stripe();
                $customer                   = $stripe->createCustomer($data);
                $str['stripe_customer_id']  = $customer->id;
                $user['referral_code']      = referralCode();
                $user->update($str);

                // insert credit point histories table

                $arrCreditPoint = [
                    'model_name' => '\App\Models\User',
                    'model_id'   => $user->id,
                    'points'     => User::SIGN_UP_POINTS,
                    'type'       => 1
                ];

                $this->insertCreditPoints($user, $arrCreditPoint);

                // Latest user id for to_user_id in User referrals
                if(isset($referred_user->id))
                {
                     // insert credit point histories table

                     $arrCreditPoint = [
                        'model_name' => '\App\Models\User',
                        'model_id'   => $user->id,
                        'points'     => UserReferrals::TO_USER_POINTS,
                        'type'       => 1
                    ];

                    $this->insertCreditPoints($user, $arrCreditPoint);
                    $referred_id['to_user_id']  = $user->id;
                    $referred_user->update($referred_id);
                }
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
    // function fetchCard(array $data) : array
    function fetchCard() : array
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
            $user->loadMissing(['devices']);

            // check same device id exist in the user devices table
            $countExistDevices = $user->devices()->where('fcm_token', $input['fcm_token'])->first();

            if( isset($countExistDevices->id) )
            {
                // skip insertion process
                return $countExistDevices;
            }
            else
            {
                // check if device limit > 4 in the db
                if( $user->devices->count() > 4 )
                {
                    // delete old fcm token
                    $fcmToken = $user->devices()->orderBy('id', 'asc')->first();

                    $fcmToken->delete();
                }
            }

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
     * Method sendLoginOtp
     *
     * @param array $input [explicite description]
     *
     * @return \App\Models\UserOtps
     *
     * @throws \App\Exceptions\GeneralException
     */
    public function sendLoginOtp(array $input): UserOtps
    {
        $otp = UserOtps::where(['country_code' => $input['country_code'], 'mobile' => $input['mobile_no'] ])->first();

        if(isset($otp->id))
        {
            // delete otp
            $otp->delete();
        }

        $n        = 4;
        $otp      = 9999;//generateNumericOTP($n);
        // $otp = generateNumericOTP($n);
        $mobile_no  = $input['country_code'].$input['mobile_no'];

        // Send OTP to User
        // sendTwilioCustomerSms($mobile_no, $otp);

        // insert login otp for that user
        $userOtp = UserOtps::create([
            'otp'           => $otp,
            'country_code'  => $input['country_code'],
            'mobile'        => $input['mobile_no']
        ]);

        if( isset( $userOtp->id ) )
        {
            return $userOtp;
        }

        throw new GeneralException('Failed to store OTP.');
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
        throw new GeneralException('Invalid OTP');
    }

    /**
     * Method VerifyOtpSms
     *
     * @param array $input [explicite description]
     *
     * @return \App\Models\User
     * @throws \App\Exceptions\GeneralException
     */
    public function VerifyOtpSms(array $input): User
    {
        // Check if the provided OTP and mobile number exist in the UserOtps table
        $userOtp = UserOtps::where('mobile', $input['mobile_no'])
            ->where('otp', $input['otp'])
            ->orderByDesc('id')
            ->first();

        if( !isset( $userOtp->id ) )
        {
            throw new GeneralException('Invalid OTP');
        }

        // delete old otp request once verified
        $userOtp->delete();

        $country = Country::where('country_code', $input['country_code'])->first();

        // check if user exist with same mobile no.
        $existingUser = User::where('phone', $input['mobile_no'])->first();

        if( !isset( $existingUser->id ) )
        {
            // create new user record
            $user = User::create([
                'phone'             => $input['mobile_no'],
                'country_code'      => $input['country_code'],
                'is_mobile_verify'  => 1,
                'country_id'        => $country->id,
            ]);

            // generate qr code based on new user
            // Create Folder & give permission
            $path = storage_path("app/public/customer_qr");
            !is_dir($path) &&
                mkdir($path, 0777, true);
            $qr_url = URL::current();
            $qr_code_image = QrCode::size(500)
                ->format('png')
                ->backgroundColor(139,149,255,0)
                ->generate($qr_url . '?user_id='.$user->id, public_path("storage/customer_qr/qrcode_$user->id.png"));


            $imageName              = "qrcode_$user->id.png";
            $user->cus_qr_code_img  = $imageName;
            $user->referral_code    = referralCode();

            $user->save();
        }
        else
        {
            // check country updated or not
            if( !isset( $existingUser->country_id ) )
            {
                $existingUser->update(['country_id' => $country->id]);
            }

            // existing user
            $user = $existingUser;
        }

        return $user;
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
            $amount         = (float) $data['amount'];

            if($default_card)
            {
                $paymentArr = [
                    'amount'        => $amount  * 100,
                    'currency'      => $user->country->code,
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

                    $user   = [
                        'credit_amount'     => (float) $user->credit_amount ?? 0,
                        'points'            => $user->points,
                    ];
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

    /**
     * Method getreferralList
     *
     * @return mixed
     */
    public function getreferralList()
    {
        $user           = auth()->user();
        $referral_list  = UserReferrals::with(['touser'])->where('from_user_id',$user->id)->get();
        return $referral_list;
    }

    /**
     * Method shareReferral
     *
     * @return mixed
     */
    public function shareReferral()
    {
        $user      = auth()->user();
        $referArr  = [];

        $referArr['from_user_id'] = $user->id;
        $referral_list  = UserReferrals::create($referArr);
        return $referral_list;
    }

    /**
     * Method getSpinResult
     *
     * @param int $type [explicite description]
     *
     * @return bool
     */
    public function getSpinResult(int $type): bool
    {
        $user = auth()->user();
        return $this->spinWheel($user, $type);
    }

    /**
     * Method deleteUserPermanently
     *
     * @param User $user [explicite description]
     *
     * @return bool
     * @throws \App\Exceptions\GeneralException
     */
    public function deleteUserPermanently(User $user): bool
    {
        try
        {
            // delete user_otps table data
            UserOtps::where('mobile', $user->phone)->delete();

            // delete user devices
            $user->devices()->delete();

            // delete payment_methods
            $user->payment_methods()->forcedelete();

            // delete favourite_items
            $user->favourite_items()->delete();

            // delete customer_tables
            CustomerTable::where('user_id' , $user->id)->forcedelete();

            // delete orders where type ORDER
            $user->orders()->forcedelete();

            // delete orders where type CART
            $user->carts()->forcedelete();

            // delete credit_point_histories
            $user->credit_points()->delete();

            // delete spins
            $user->spins()->delete();

            // delete gift_cards
            UserGiftCard::where(function($query) use($user)
            {
                $query->where('user_id', $user->id);
                $query->orWhere('from_user', $user->id);
                $query->orWhere('to_user', $user->id);
                $query->orWhere('verify_user_id', $user->id);
            })->delete();

            // delete user_refferrals
            UserReferrals::where(function($query) use($user)
            {
                $query->where('from_user_id', $user->id);
                $query->orWhere('to_user_id', $user->id);
            })->delete();

            // delete friendships
            FriendRequest::where(function($query) use($user)
            {
                $query->where('user_id', $user->id);
                $query->orWhere('friend_id', $user->id);
            })->delete();

            // delete all personal access tokens
            $user->tokens()->delete();

            // delete user
            return $user->delete();

        }
        catch (\Exception $e)
        {
            throw new GeneralException($e->getMessage());
        }
    }
}