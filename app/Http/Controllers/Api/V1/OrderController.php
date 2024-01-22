<?php

namespace App\Http\Controllers\Api\V1;

use App\Exceptions\GeneralException;
use App\Http\Requests\AddtocartRequest;
use App\Http\Requests\CartDeleteRequest;
use App\Http\Requests\CustomerOrderRequest;
use App\Http\Requests\OrderDeleteItemRequest;
use App\Http\Requests\OrderHistoryRequest;
use App\Http\Requests\OrderReviewRequest;
use App\Http\Requests\OrderUpdateRequest;
use App\Http\Requests\PlaceOrderRequest;
use App\Http\Resources\MyFriendsResource;
use App\Http\Resources\OrderListResource;
use App\Http\Resources\OrderResource;
use App\Http\Resources\UserResource;
use App\Http\Resources\VenueUserResource;
use App\Models\Order;
use App\Repositories\OrderRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderController extends APIController
{
    /** @var \App\Repositories\OrderRepository $repository */
    protected $repository;

     /**
     * Method __construct
     *
     * @param OrderRepository $repository [explicite description]
     *
     * @return void
     */
    public function __construct(OrderRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Method show
     *
     * @param Request $request [explicite description]
     * @param Order $order [explicite description]
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, Order $order)
    {
        if( isset( $order->id ) )
        {
            return $this->respondSuccess('Order detail found.', new OrderResource($order));
        }

        throw new GeneralException('Order not found.');
    }

    /**
     * Method addToCart
     *
     * @param AddtocartRequest $request [explicite description]
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function addToCart(AddtocartRequest $request)
    {
        // sample request
        /*
        {
            "order": {
                "pickup_point_id": 2,
                "type": 1,              // 1=CART, 2=ORDER
                "total": 50,
                "currency_id": 1,
                "restaurant_id": 4
            },
            "order_items": [
                {
                    "item_id": 69,
                    "category_id": 2,
                    "price": 0,
                    "quantity": 2,
                    "mixer": {
                        "id": 361,
                        "price": 0,
                        "quantity": 1
                    },
                    "addons": [
                        {
                            "id": 283,
                            "price": 16,
                            "quantity": 1
                        },
                        {
                            "id": 284,
                            "price": 10,
                            "quantity": 1
                        }
                    ],
                    "variation": {
                        "id" : 61,
                        "price": 15,
                        "quantity": 1
                    }
                },
                {
                    "item_id": 69,
                    "category_id": 2,
                    "price": 0,
                    "quantity": 2,
                    "mixer": {
                        "id": 361,
                        "price": 0,
                        "quantity": 1
                    },
                    "addons": [
                        {
                            "id": 283,
                            "price": 16,
                            "quantity": 1
                        },
                        {
                            "id": 284,
                            "price": 10,
                            "quantity": 1
                        }
                    ],
                    "variation": {
                        "id" : 61,
                        "price": 15,
                        "quantity": 1
                    }
                }
            ]
        }
        */

        $requestData    = $request->all();
        $order          = $this->repository->addTocart($requestData);

        return $this->respondSuccess('Order created successfully.');
    }

    /**
     * Method viewCart
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function viewCart()
    {
        $cart_data      = $this->repository->getCartdata();
        // dd($cart_data);

        if($cart_data)
        {
            return $this->respondSuccess('Cart data found', new OrderResource($cart_data));
        }

        return $this->respondWithError('Your cart is empty.');
    }

    /**
     * Method orderHistory
     *
     * @return \Illuminate\Http\JsonResponse
     */
    function orderHistory(OrderHistoryRequest $request)
    {
        $order_data      = $this->repository->getOrderdata($request->validated());

        if($order_data)
        {
            $data = [
            'total_orders'   => $order_data['total_orders'],
            'orders'         => $order_data['total_orders'] ? OrderListResource::collection($order_data['orders']) :  ''
        ];
            return $this->respondSuccess('Order data found', $data);
        }

        return $this->respondWithError('Your order not found.');
    }

    /**
     * Method cartCount
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function cartCount()
    {
        $data      = $this->repository->getCartCount();

        if($data)
        {
            return $this->respondSuccess('Cart data found', $data);
        }
    }

    public function rankBenefits()
    {
        $data       =   $this->repository->getRankBenifit();
        if($data)
        {
            return $this->respondSuccess('Rank data found', $data);
        }
    }

    /**
     * Method deleteItem
     *
     * @param OrderDeleteItemRequest $request [explicite description]
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteItem(OrderDeleteItemRequest $request)
    {
        $order_data   = $this->repository->deleteItem($request->validated());

        return $this->respondSuccess('Order data found', new OrderResource($order_data));
    }

    /**
     * Method updateCart
     *
     * @param Request $request [explicite description]
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateCart(Request $request)
    {
        $input = $request->all();

        $order = Order::findOrFail($input['order_id']);

        $order->loadMissing(['order_items']);

        $order          = $this->repository->updateCart($order, $input);

        return $this->respondSuccess('Order updated successfully.', new OrderResource($order));
    }


    /**
     * Method deleteCart
     *
     * @param CartDeleteRequest $request [explicite description]
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteCart(CartDeleteRequest $request)
    {
        $order_data   = $this->repository->deleteCart($request->validated());

        return $this->respondSuccess('Order deleted successfully');
    }

    /**
     * Method placeOrder
     *
     * @param PlaceOrderRequest $request [explicite description]
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function placeOrder(PlaceOrderRequest $request)
    {
        $place_order = $this->repository->placeOrder($request->validated());

        return $this->respondSuccess('Order payment successfully.', new OrderResource($place_order));
    }

    /**
     * Method currentOrder
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function currentOrder()
    {
        $currentOrder      = $this->repository->getCurrentOrder();

        if(isset( $currentOrder->id ))
        {
            return $this->respondSuccess('order data found', new OrderResource($currentOrder));
        }

        return $this->respondWithError('Your order is empty.');
    }

    /**
     * Method orderUpdate
     *
     * @param CustomerOrderRequest $request [explicite description]
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function orderStatusUpdate(CustomerOrderRequest $request)
    {
        $currentOrder      = $this->repository->updateOrderStatus($request->validated());

        if(isset( $currentOrder->id ))
        {
            return $this->respondSuccess('order is cancelled');
        }

        return $this->respondWithError('Your order is empty.');
    }

    /**
     * Method orderReview
     *
     * @param OrderReviewRequest $request [explicite description]
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function orderReview(OrderReviewRequest $request)
    {
        $reviewOrder      = $this->repository->ReviewOrder($request->validated());

        if(isset( $reviewOrder->id ))
        {
            return $this->respondSuccess('Order Feedback is received');
        }

        return $this->respondWithError('Feedback is not received.');
    }

    /**
     * Method reOrder
     *
     * @param Request $request [explicite description]
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function reOrder(Request $request)
    {
        $input      = $request->all();
        $reorder    = $this->repository->reOrder($input);

        if(isset($reorder->id))
        {
            return $this->respondSuccess('Cart data found', new OrderResource($reorder));
        }
        return $this->respondWithError('Failed to Re-order.');
    }

    public function venueList(Request $request)
    {
        $input  =   $request->all();
        $venueList  = $this->repository->venueUserList($input);
        if($venueList->count())
        {
            return $this->respondSuccess('Venue User List', VenueUserResource::collection($venueList));
        }

        throw new GeneralException('No user found in this restaurant.');
    }


    public function sendFriendRequest(Request $request)
    {
        $input  = $request->all();
        $friends    = $this->repository->sendFriendReq($input);
        return $this->respondSuccess('Friend Request Send Successfully', $friends);
    }

    public function friendRequestStatus(Request $request)
    {
        $input  = $request->all();
        $friendStatus   =   $this->repository->friendRequestStatus($input);
        if($input['status'] == 1)
        {
            return $this->respondSuccess('Friend Request Accepted Successfully', $friendStatus);
        } else {
            return $this->respondSuccess('Friend Request Decline Successfully', $friendStatus);
        }
    }


    public function pendingFriendRequest(Request $request)
    {
        $friends    = $this->repository->pendingFriendReq($request->all());
        return $this->respondSuccess('New Friend Request Successfully', MyFriendsResource::collection($friends));
    }

    public function printOrder(Request $request,Order $order)
    {
        $order      = $order->id;
        $printOrder = $this->repository->printOrder($order);
        $data = [
            'url'   => $printOrder,
        ];
        return $this->respondSuccess('PDF generated', $data);

        // $restaurant  = $order->restaurant->owners()->first();
        // return view('pdf.index', [
        //     'order'         => $order,
        //     'restaurant'    => $restaurant
        // ]);
    }

    public function giftCredits(Request $request)
    {
        $input  = $request->all();
        $giftCredits    = $this->repository->giftCreditSend($input);
        return $this->respondSuccess("Gift credits has been sent successfully.",$giftCredits);
    }

    public function userProfile(Request $request)
    {
        $input  = $request->all();
        $userProfile = $this->repository->userProfileData($input);
        return $this->respondSuccess('Get user profile successfully ', new UserResource($userProfile));
    }

    public function friendShip()
    {
        // $input          = $request->all();
        $myFriendList   = $this->repository->myFriendList();
        if($myFriendList->count())
        {
            return $this->respondSuccess('Venue User List', MyFriendsResource::collection($myFriendList));
        }

        throw new GeneralException('No user found in this restaurant.');
    }
}
