<?php

namespace App\Http\Controllers\Api\V1\Waiter;

use App\Http\Controllers\Api\V1\APIController;
use App\Http\Requests\AddtocartRequest;
use App\Http\Requests\RestaurantItemSearchRequest;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\CategorySubCategoryResource;
use App\Http\Resources\OrderResource;
use App\Http\Resources\RestaurantItemsResource;
use App\Models\Order;
use App\Repositories\OrderRepository;
use App\Repositories\RestaurantRepository;
use App\Repositories\WaiterRepositiory;
use Illuminate\Http\Request;

class HomeController extends APIController
{
    protected $repository;

    /** @var \App\Repositories\RestaurantRepository */
    protected $restaurantRepository;

    /** @var \App\Repositories\OrderRepository */
    protected $orderRepository;

    public function __construct(WaiterRepositiory $repository, RestaurantRepository $restaurantRepository, OrderRepository $orderRepository)
    {
        $this->repository = $repository;
        $this->restaurantRepository = $restaurantRepository;
        $this->orderRepository      = $orderRepository;
    }

    public function activeTable()
    {
        $auth_waiter = auth('api')->user();
        $orderTbl = Order::where('waiter_id',$auth_waiter->id)->where('type',Order::ORDER)->get();
        $kitchen_status = Order::where('type',Order::ORDER)->whereIn('status',[Order::KITCHEN_CONFIRM,Order::READYFORPICKUP])->get();
        $data = [
            'active_tables' => $orderTbl->count() ? OrderResource::collection($orderTbl) : [],
            'kitchen_status' => $kitchen_status->count() ? OrderResource::collection($kitchen_status) : [],
        ];
        return $this->respondSuccess('Waiter Order Fetched successfully.', $data);
    }

    public function gostatus(Request $request)
    {
        $input          = $request->all();
        $restaurant_waiter   = $this->repository->updateStatus($input,1);

        return $this->respondSuccess('Status updated');
    }


    public function itemSearchByName(Request $request)
    {
        // dd($request->all());
        $restaurant_items = $this->repository->getItemByName($request->all());
        if($restaurant_items->count())
        {
            return $this->respondSuccess('Items Found', RestaurantItemsResource::collection($restaurant_items));
        }
        return $this->respondWithError('Items not found.');
    }


    public function categoryList(Request $request)
    {
        $user = auth()->user();
        $user->loadMissing(['restaurant_waiter', 'restaurant_waiter.restaurant', 'restaurant_waiter.restaurant.main_categories', 'restaurant_waiter.restaurant.main_categories.children']);
        $categories = $user->restaurant_waiter->restaurant->main_categories()->with(['children'])->get();
        if($user->restaurant_waiter->restaurant->main_categories->count())
        {
            $data = [
                'categories' => $categories->count() ? CategorySubCategoryResource::collection($user->restaurant_waiter->restaurant->main_categories) : [],
            ];
            return $this->respondSuccess('Category Found', $data);
        }
        return $this->respondWithError('Category not found.');
    }

    public function restaurantItemListByCategory(Request $request)
    {
        $user = auth()->user();
        $user->loadMissing(['restaurant_waiter']);
        $req_data = [
            'is_available' => 1,
            'restaurant_id' => $user->restaurant_waiter->restaurant_id,
            'category_id' => $request->category_id,
        ];
        $restaurant_items = $this->restaurantRepository->getRestaurantItems($req_data);

        if( $restaurant_items->count() )
        {
            return $this->respondSuccess('Items Found.', RestaurantItemsResource::collection($restaurant_items));
        }

        return $this->respondWithError('Items not found.');
    }

    public function addToCart(AddtocartRequest $request)
    {
        $requestData = $request->all();
        $order       = $this->orderRepository->addTocart($requestData);

        return $this->respondSuccess('Order created successfully.');
    }
}
