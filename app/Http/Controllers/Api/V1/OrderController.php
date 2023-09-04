<?php

namespace App\Http\Controllers\Api\V1;

use App\Exceptions\GeneralException;
use App\Http\Requests\AddtocartRequest;
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
     * @return void
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
     * @return void
     */
    public function viewCart()
    {
        $cart_data      = $this->repository->getCartdata();
        // dd($cart_data);

        if($cart_data)
        {
            return $this->respondSuccess('Cart data found', OrderResource::collection($cart_data));
        }


    }
}
