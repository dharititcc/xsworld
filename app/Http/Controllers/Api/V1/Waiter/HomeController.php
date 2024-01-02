<?php

namespace App\Http\Controllers\Api\V1\Waiter;

use App\Exceptions\GeneralException;
use App\Http\Controllers\Api\V1\APIController;
use App\Http\Requests\AddtocartRequest;
use App\Http\Requests\OrderHistoryRequest;
use App\Http\Requests\PlaceOrderRequest;
use App\Http\Resources\CategorySubCategoryResource;
use App\Http\Resources\KitchenStatusResource;
use App\Http\Resources\OrderResource;
use App\Http\Resources\RestaurantItemsResource;
use App\Http\Resources\TableResource;
use App\Http\Resources\WaiterOrderListResource;
use App\Models\CustomerTable;
use App\Models\Order;
use App\Models\OrderSplit;
use App\Repositories\OrderRepository;
use App\Repositories\RestaurantRepository;
use App\Repositories\WaiterRepositiory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

    /**
     * Method activeTable
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function activeTable()
    {
        $auth_waiter = auth()->user();

        // load missing restaurant waiter and restaurant
        $auth_waiter->loadMissing(['restaurant_waiter', 'restaurant_waiter.restaurant']);

        // only booked table with no orders available
        $bookedTableModel = CustomerTable::select([
            'customer_tables.id',
            'customer_tables.user_id',
            'customer_tables.restaurant_table_id',
            'customer_tables.order_id',
            'restaurant_tables.restaurant_id',
            DB::raw("0 as waiter_status")
        ])
        ->with([
            'user',
            'table_order',
            'table_order.restaurant',
            'table_order.order_items',
        ])
        ->whereNull('order_id')
        ->leftJoin('restaurant_tables', 'restaurant_tables.id', '=', 'customer_tables.restaurant_table_id')
        ->where('restaurant_tables.restaurant_id', $auth_waiter->restaurant_waiter->restaurant->id)
        ->get();

        // customer booked table and has order
        $bookedOrderTable = Order::select([
            'orders.id',
            'orders.user_id',
            'orders.restaurant_table_id',
            'orders.id AS order_id',
            'orders.restaurant_id',
            'orders.waiter_status'
        ])
        ->with([
            'restaurant_table',
            'order_items',
            'order_items.restaurant_item',
            'order_items.restaurant_item.restaurant',
            'order_items.restaurant_item.restaurant.currency',
            'order_items.restaurant_item.restaurant.country',
            'order_items.variation',
            'order_items.mixer',
            'order_items.mixer.restaurant_item',
            'order_items.addons',
            'order_items.addons.restaurant_item',
        ])
        ->where('restaurant_id', $auth_waiter->restaurant_waiter->restaurant->id)
        ->whereNotNull('restaurant_table_id')
        ->whereIn('waiter_status', [Order::WAITER_PENDING, Order::CURRENTLY_BEING_PREPARED, Order::CURRENTLY_BEING_SERVED, Order::CURRENTLY_BEING_PREPARED, Order::READY_FOR_COLLECTION])
        ->get();

        $orderTbl = $bookedOrderTable->merge($bookedTableModel);

        // $kitchen_status = Order::where('type',Order::ORDER)->where('waiter_id', $auth_waiter->id)->whereIn('status',[Order::READYFORPICKUP,Order::WAITER_PENDING, Order::CURRENTLY_BEING_PREPARED])->get();  //Order::KITCHEN_CONFIRM, remove
        $kitchen_status = Order::where('type',Order::ORDER)
        ->with([
            'restaurant',
            'restaurant.currency',
            'restaurant.country',
            'user',
            'restaurant_pickup_point',
            'pickup_point_user',
            'order_split_food',
            'restaurant_table',
            'order_items'
        ])
        // ->where('waiter_id', $auth_waiter->id)
        ->where('type', Order::ORDER)
        ->whereNotNull('restaurant_table_id')
        ->whereHas('order_split_food', function($query){
            $query->where('status', OrderSplit::READYFORPICKUP);
            $query->orWhere('status', OrderSplit::CURRENTLY_BEING_PREPARED);
        })->get();

        $data = [
            'active_tables'             => $orderTbl->count() ? TableResource::collection($orderTbl) : [],
            'kitchen_status'            => $kitchen_status->count() ? KitchenStatusResource::collection($kitchen_status) : [],
        ];
        return $this->respondSuccess('Waiter Order Fetched successfully.', $data);
    }

    /**
     * Method gostatus
     *
     * @param Request $request [explicite description]
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function gostatus(Request $request)
    {
        $input          = $request->all();
        $restaurant_waiter   = $this->repository->updateStatus($input,1);

        return $this->respondSuccess('Status updated');
    }

    /**
     * Method itemSearchByName
     *
     * @param Request $request [explicite description]
     *
     * @return \Illuminate\Http\JsonResponse
     */
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

    /**
     * Method categoryList
     *
     * @param Request $request [explicite description]
     *
     * @return \Illuminate\Http\JsonResponse
     */
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

    /**
     * Method getFeaturedItemsByCatID
     *
     * @param Request $request [explicite description]
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getFeaturedItemsByCatID(Request $request)
    {
        $user = auth()->user();
        $user->loadMissing(['restaurant_waiter', 'restaurant_waiter.restaurant']);
        $data = [
            'restaurant_id' => $user->restaurant_waiter->restaurant_id,
            'category_id'   => $request->category_id,
        ];
        $featured_items = $this->restaurantRepository->getFeaturedItems($data);
        if($featured_items->count())
        {
            $items = $featured_items->count() ? RestaurantItemsResource::collection($featured_items) : [];
            return $this->respondSuccess('Featured Items Found', $items);
        }
        return $this->respondWithError('Featured Items not found.');
    }

    /**
     * Method restaurantItemListByCategory
     *
     * @param Request $request [explicite description]
     *
     * @return \Illuminate\Http\JsonResponse
     */
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

    /**
     * Method addToCart
     *
     * @param AddtocartRequest $request [explicite description]
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function addToCart(AddtocartRequest $request)
    {
        $requestData = $request->all();
        $order       = $this->orderRepository->addTocart($requestData);

        return $this->respondSuccess('Order created successfully.',$order->id);
    }

    /**
     * Method viewCart
     *
     * @param Request $request [explicite description]
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function viewCart(Request $request)
    {
        $cart_data = $this->orderRepository->getCartdataWaiter($request->all());
        if($cart_data)
        {
            return $this->respondSuccess('Cart data found', new OrderResource($cart_data));
        }

        return $this->respondWithError('Your cart is empty.');
    }

    /**
     * Method orderHistory
     *
     * @param OrderHistoryRequest $request [explicite description]
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function orderHistory(OrderHistoryRequest $request)
    {
        $order_data = $this->orderRepository->getwaiterOrderdata($request->validated());
        if($order_data)
        {
            $data = [
                'total_order' => $order_data['total_orders'],
                'orders'      => $order_data['total_orders'] ? WaiterOrderListResource::collection($order_data['orders']) : ''
            ];
            return $this->respondSuccess('Order data found', $data);
        }
        return $this->respondWithError('Your order not found.');
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
        $place_order = $this->orderRepository->placeOrderwaiter($request->validated());
        return $this->respondSuccess('Order payment successfully.', new OrderResource($place_order));
    }

    /**
     * Method waiterupdateCart
     *
     * @param Request $request [explicite description]
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function waiterupdateCart(Request $request)
    {
        $input = $request->all();
        $order = Order::findOrFail($input['order_id']);
        $order->loadMissing(['order_items']);

        $order = $this->orderRepository->updateCart($order,$input);
        return $this->respondSuccess('Order updated successfully',new OrderResource($order));
    }

    /**
     * Method waiterPayment
     *
     * @param Request $request [explicite description]
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function waiterPayment(Request $request)
    {
        $takePayment = $this->orderRepository->takePayment($request->all());
        return $this->respondSuccess('Payment successfully', new OrderResource(($takePayment)));
    }

    /**
     * Method addCard
     *
     * @param Request $request [explicite description]
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function addCard(Request $request)
    {
        $cardDetails = $this->orderRepository->addNewCard($request->all());
        return $this->respondSuccess('New Card Added successfully,',$cardDetails);
    }

    /**
     * Method tableList
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function tableList()
    {
        $orderTable = $this->orderRepository->tableOrderLists();
        return $this->respondSuccess('Table List successfully', WaiterOrderListResource::collection(($orderTable)));
    }

    /**
     * Method addCusToTbl
     *
     * @param Request $request [explicite description]
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function addCusToTbl(Request $request)
    {
        $CusToTbl = $this->orderRepository->customerTable($request->all());
        if(empty($CusToTbl)) {
            throw new GeneralException('Table Not Addedd');
        }
        return $this->respondSuccess("Table Allocated Successfully", $CusToTbl);
    }

    /**
     * Method endWaiterSession
     *
     * @param Request $request [explicite description]
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function endWaiterSession(Request $request)
    {
        $CusToTblDel = $this->orderRepository->customerTableDel($request->all());
        if(!$CusToTblDel) {
            throw new GeneralException('Table Not Found');
        }
        return $this->respondSuccess("Table Data Remove Successfully");
    }
}
