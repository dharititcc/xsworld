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
        $dataArr = [
            'restaurant_item_id'  => $request->restaurant_item_id,
            'category_id'         => $request->category_id,
            'price'               => $request->price,
            'quantity'            => $request->quantity,
            'is_variable'         => $request->is_variable,
            'mixer'               => $request->mixer ?? null,
            'addon'               => $request->addon ?? null,
            'variation'           => $request->variation ?? null
        ];

        $add_to_cart   = $this->repository->addTocart($dataArr);

        if($add_to_cart)
        {
            return $this->respondSuccess('Item added');
        }

        return $this->respondWithError('Item not added.');
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
