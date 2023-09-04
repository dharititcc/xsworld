<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\AddtocartRequest;
use App\Http\Resources\OrderResource;
use App\Repositories\OrderRepository;
use Illuminate\Database\Eloquent\Collection;

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
                "type": 0,              // 0=CART, 1=ORDER
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
                        "price": 0
                    },
                    "addons": [
                        {
                            "id": 283,
                            "price": 16
                        },
                        {
                            "id": 284,
                            "price": 10
                        }
                    ],
                    "variation": {
                        "id" : 61,
                        "price": 15
                    }
                },
                {
                    "item_id": 69,
                    "category_id": 2,
                    "price": 0,
                    "quantity": 2,
                    "mixer": {
                        "id": 361,
                        "price": 0
                    },
                    "addons": [
                        {
                            "id": 283,
                            "price": 16
                        },
                        {
                            "id": 284,
                            "price": 10
                        }
                    ],
                    "variation": {
                        "id" : 61,
                        "price": 15
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
