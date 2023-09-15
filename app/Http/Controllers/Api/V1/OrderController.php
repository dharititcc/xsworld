<?php

namespace App\Http\Controllers\Api\V1;

use App\Exceptions\GeneralException;
use App\Http\Requests\AddtocartRequest;
use App\Http\Requests\CartDeleteRequest;
use App\Http\Requests\OrderDeleteItemRequest;
use App\Http\Requests\OrderUpdateRequest;
use App\Http\Requests\PlaceOrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Repositories\OrderRepository;
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
    function orderHistory()
    {
        $order_data      = $this->repository->getOrderdata();

        if($order_data)
        {
            return $this->respondSuccess('Order data found', OrderResource::collection($order_data));
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
     * @return void
     */
    public function placeOrder(PlaceOrderRequest $request)
    {
        $place_order = $this->repository->placeOrder($request->validated());

        return $this->respondSuccess('Order payment successfully.', new OrderResource($place_order));
    }
}
