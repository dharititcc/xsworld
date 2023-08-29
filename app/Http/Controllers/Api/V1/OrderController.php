<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\AddtocartRequest;
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

        dd($add_to_cart->count());
    }
}
