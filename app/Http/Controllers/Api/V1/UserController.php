<?php

namespace App\Http\Controllers\Api\V1;

use App\Billing\Stripe;
use App\Exceptions\GeneralException;
use App\Http\Resources\UserResource;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use App\Http\Requests\UpdateProfileRequest;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\DeleteCardRequest;
use App\Http\Requests\FetchCardRequest;
use App\Http\Requests\ProfileRequest;
use App\Http\Requests\PurchaseGiftCardRequest;
use App\Http\Requests\UserFavouriteItemsRequest;
use App\Http\Resources\SpinWinningResource;
use App\Http\Resources\UserReferralResource;
use App\Models\Spin;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class UserController extends APIController
{
    /** @var \App\Repositories\UserRepository $repository */
    protected $repository;

    /**
     * Method __construct
     *
     * @param \App\Repositories\UserRepository $repository [explicite description]
     *
     * @return void
     */
    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
        $this->middleware('auth');
    }

    /**
     * Method changePassword
     *
     * @param \App\Http\Requests\ChangePasswordRequest $request [explicite description]
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\GeneralException
     */
    /**
     * @OA\PATCH(
     ** path="/api/v1/users/change-password",
     *   tags={"Users"},
     *   summary="change-password",
     *     security={
     *         {"bearer_token": {}}
     *     },
     *
     *     @OA\Parameter(
     *      name="old_password",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     * ),
     *     @OA\Parameter(
     *      name="new_password",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     * ),
     *    @OA\Response(
     *      response=200,
     *       description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=401,
     *       description="Unauthenticated"
     *   ),
     *)
     **/
    public function changePassword(ChangePasswordRequest $request)
    {
        if(!Hash::check($request->get('old_password'), auth()->user()->password))
        {
            return $this->respondWithError('Current password does not match.');
        }

        $user = auth()->user();
        // update password other data
        $dataArr = [
            'password'              =>Hash::make($request->new_password)
        ];

        if( $this->repository->update($dataArr, $user) )
        {
            return $this->respond([
                'status'    =>  true,
                'message'   =>  'Update Password successful',
            ]);
        }

        throw new GeneralException('Password is failed to update.');
    }

    /**
     * Method favourite and unfavourite items
     *
     * @param \App\Http\Requests\UserFavouriteItemsRequest $request [explicite description]
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\GeneralException
     */
    /**
     * @OA\Post(
     ** path="/api/v1/users/favourite",
     *   tags={"Users"},
     *   summary="User's Favourite Items",
     *     security={
     *         {"bearer_token": {}}
     *     },
     *
     *     @OA\Parameter(
     *      name="restaurant_item_id",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="number"
     *      )
     * ),
     *    @OA\Response(
     *      response=200,
     *       description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=401,
     *       description="Unauthenticated"
     *   ),
     *)
     **/
    public function favorite(UserFavouriteItemsRequest $request)
    {
        /** @var \App\Models\User */
        $user = auth()->user();
        $data = $request->validated();

        $this->repository->addFavourite($data, $user);

        $count = $this->repository->checkFavouriteItemExist($data['restaurant_item_id'], $user);

        if( $count > 0 )
        {
            return $this->respond([
                'status' => true,
                'message'=> 'Favourite Items added successfully.'
            ]);
        }
        else
        {
            return $this->respond([
                'status' => true,
                'message'=> 'Favourite Items removed successfully.'
            ]);
        }
    }

    /**
     * Method me
     *
     * @param \Illuminate\Http\Request $request [explicite description]
     *
     * @return \Illuminate\Http\JsonResponse
     */
    /**
     * @OA\Get(
     ** path="/api/v1/users/get-profile",
     *   tags={"Users"},
     *   summary="get-profile",
     *   operationId="get-profile",
     *   security={
     *         {"bearer_token": {}}
     *     },
     *   @OA\Response(
     *      response=200,
     *       description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=401,
     *       description="Unauthenticated"
     *   ),
     *)
     **/
    public function me(Request $request)
    {
        $user = auth()->user();
        return $this->respond([
            'status' => true,
            'message'=> 'Get Profile successfully.',
            'item'   => new UserResource($user)
        ]);
    }

    /**
     * Method updateProfile
     *
     * @param \App\Http\Requests\UpdateProfileRequest $request [explicite description]
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\GeneralException
     */
    /**
     * @OA\Post(
     ** path="/api/v1/users/update-profile",
     *   tags={"Users"},
     *
     *   summary="update-profile",
     *     security={
     *         {"bearer_token": {}}
     *     },
     *  @OA\RequestBody(
     *  @OA\MediaType(
     *           mediaType="multipart/form-data",
     *      )
     * ),
     *     @OA\Parameter(
     *      name="first_name",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     * ),
     *   @OA\Parameter(
     *      name="last_name",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     * ),
     *     @OA\Parameter(
     *      name="phone",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     * ),
     *     @OA\Parameter(
     *      name="phone2",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *    @OA\Parameter(
     *      name="email",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *    @OA\Parameter(
     *      name="country_code",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *     @OA\Parameter(
     *      name="birth_date",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *      @OA\Parameter(
     *      name="address",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="profile_image",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="file"
     *      )
     *   ),
     *    @OA\Response(
     *      response=200,
     *       description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=401,
     *       description="Unauthenticated"
     *   ),
     *)
     **/
    public function updateProfile(UpdateProfileRequest $request)
    {
        $user       = auth()->user();
        $dataArr    = [
            'first_name'    => $request->get('first_name') ?? null,
            'last_name'     => $request->get('last_name') ?? null,
            'phone'         => $request->get('phone') ?? null,
            'phone2'        => $request->get('phone2') ?? null,
            'birth_date'    => $request->get('birth_date') ?? null,
            'address'       => $request->get('address') ?? null,
            'email'         => $request->get('email') ?? null,
            'country_code'  => $request->get('country_code') ?? null,
            'profile_image' => $request->file('profile_image') ?? null,
        ];

        if( $this->repository->update($dataArr, $user) )
        {
            $user->refresh();
            return $this->respond([
                'status' => true,
                'message'=> 'Profile updated successfully.',
                'item'   => new UserResource($user)
            ]);
        }

        throw new GeneralException('Profile is failed to update.');
    }

    /**
     * Method storeUserData
     *
     * @param ProfileRequest $request [explicite description]
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeUserData(ProfileRequest $request): JsonResponse
    {
        $user       = auth()->user();
        $data       = $request->validated();
        $fullName   = explode(' ', $data['full_name']);

        $profileData = [
            'first_name'            => $fullName[0],
            'last_name'             => isset($fullName[1]) ? $fullName[1] : null,
            'birth_date'            => $data['birth_date'],
            'email'                 => $data['email'],
            'platform'              => isset( $data['platform'] ) ? $data['platform'] : null,
            'os_version'            => isset( $data['os_version'] ) ? $data['os_version'] : null,
            'application_version'   => isset( $data['application_version'] ) ? $data['application_version'] : null,
            'model'                 => isset( $data['model'] ) ? $data['model'] : null,
        ];

        $this->repository->update($profileData, $user);

        return $this->respondSuccess('Profile data stored successfully.', new UserResource($user));
    }

    public function resetPassword(Request $request)
    {
        $validated  = $request->validate(['email' => "required|email"]);
       // return \Response::json($arr);
    }

    /**
     * Method fetchCard
     *
     * @param FetchCardRequest $request [explicite description]
     *
     * @return \Illuminate\Http\JsonResponse
     */
    // public function fetchCard(FetchCardRequest $request)
    public function fetchCard()
    {
    //    $card_data = $this->repository->fetchCard($request->validated());
       $card_data = $this->repository->fetchCard();

       if(!empty($card_data))
       {
            return $this->respond([
                'status' => true,
                'message'=> 'User cards retrieved.',
                'item'   => $card_data
            ]);
       }

       throw new GeneralException('There is no card found');
    }

    /**
     * Method delectcard
     *
     * @param DeleteCardRequest $request [explicite description]
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function delectcard(DeleteCardRequest $request)
    {
        $card = $this->repository->deleteUserCard($request->validated());

        if($card)
        {
            return $this->respond([
                'status' => true,
                'message'=> 'User card deleted.'
            ]);
        }

        throw new GeneralException('There is no card found');
    }

    /**
     * Method generateCardToken
     *
     * @param Request $request [explicite description]
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function generateCardToken(Request $request)
    {
        $user  = auth()->user();
        $input = $request->all();
        $input['name'] = $user->name;

        $stripe = new Stripe();

        $token  = $stripe->createToken($input);

        dd($token);
    }

    /**
     * Method attachCard
     *
     * @param Request $request [explicite description]
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function attachCard(Request $request): JsonResponse
    {
        $input          = $request->all();
        $cards          = $this->repository->attachCard($input);

        return $this->respondSuccess('Credit card attached successfully.');
    }

    /**
     * Method markDefaultCard
     *
     * @param Request $request [explicite description]
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function markDefaultCard(Request $request)
    {
        $input          = $request->all();

        if( $this->repository->markDefaultCard($input) )
        {
            return $this->respondSuccess('Marked default credit card.');
        }

        throw new GeneralException('Mark default credit card is failed.');
    }

    /**
     * Method purchaseGiftCard
     *
     * @param PurchaseGiftCardRequest $request [explicite description]
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function purchaseGiftCard(PurchaseGiftCardRequest $request)
    {
        $gift_card = $this->repository->purchaseGiftCard($request->validated());

        if( $gift_card )
        {
            return $this->respondSuccess('Gift card purchased successfully');
        }

        throw new GeneralException('Gift card purchase failed.');
    }

    /**
     * Method redeemGiftCard
     *
     * @param Request $request [explicite description]
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function redeemGiftCard(Request $request)
    {
        $input              = $request->all();
        $redeem_gift_card   = $this->repository->redeemGiftCard($input);
        if( $redeem_gift_card )
        {
            return $this->respondSuccess('Gift card redeemed successfully',$redeem_gift_card);
        }
        throw new GeneralException('Gift card redeemed failed.');
    }

    /**
     * Method referralList
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function referralList()
    {
        $referral_list        = $this->repository->getreferralList();

        if( $referral_list->count() )
        {
            return $this->respondSuccess('Referral list Found.', UserReferralResource::collection($referral_list));
        }

        return $this->respondWithError('Referral not found.');
    }

    /**
     * Method shareReferral
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function shareReferral()
    {
        $share_referral     = $this->repository->shareReferral();
    }

    /**
     * Method getSpinResult
     *
     * @param Request $request [explicite description]
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSpinResult(Request $request)
    {
        $resultOneX     = (int) $this->repository->getSpinResult(Spin::ONE_X);
        $resultFiveX    = (int) $this->repository->getSpinResult(Spin::FIVE_X);
        $resultTenX     = (int) $this->repository->getSpinResult(Spin::TEN_X);

        return $this->respond([
            'status' => true,
            'message'=> 'Spin result before you spin.',
            'item'   => [
                'one_x'     => $resultOneX,
                'five_x'    => $resultFiveX,
                'ten_x'     => $resultTenX,
            ]
        ]);
    }

    /**
     * Method storeSpin
     *
     * @param Request $request [explicite description]
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeSpin(Request $request)
    {
        $spin = $this->repository->storeSpin($request->all());

        $resultOneX     = (int) $this->repository->getSpinResult(Spin::ONE_X);
        $resultFiveX    = (int) $this->repository->getSpinResult(Spin::FIVE_X);
        $resultTenX     = (int) $this->repository->getSpinResult(Spin::TEN_X);

        return $this->respondSuccess('Spin stored successfully.', [
            'one_x'     => $resultOneX,
            'five_x'    => $resultFiveX,
            'ten_x'     => $resultTenX,
        ]);
    }

    /**
     * Method myWinning
     *
     * @param Request $request [explicite description]
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function myWinning(Request $request)
    {
        $result = $this->repository->myWinning();

        if( $result->count() )
        {
            return $this->respondSuccess('Winning list found.', SpinWinningResource::collection($result));
        }

        throw new GeneralException('There is no winning in the spin game yet.');
    }
}
