<?php

namespace App\Http\Controllers\Api\V1\Waiter;

use App\Http\Controllers\Api\V1\APIController;
use App\Http\Requests\AddtocartRequest;
use App\Http\Requests\OrderHistoryRequest;
use App\Http\Requests\PlaceOrderRequest;
use App\Http\Requests\RestaurantItemSearchRequest;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\CategorySubCategoryResource;
use App\Http\Resources\OrderListResource;
use App\Http\Resources\OrderResource;
use App\Http\Resources\RestaurantItemsResource;
use App\Http\Resources\TableResource;
use App\Models\CustomerTable;
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
        $orderTbl = CustomerTable::select(['customer_tables.*'])
        ->leftJoin('orders', 'customer_tables.order_id','=','orders.id')
        ->with(['table_order'])
        ->where('customer_tables.waiter_id',$auth_waiter->id)
        ->get();
        // echo common()->formatSql($orderTbl);die;
        dd($orderTbl);
        // $orderTbl = Order::with(['user','restaurant','restaurant_table'])->where('waiter_id',$auth_waiter->id)->where('type',Order::CART)->get();
        $kitchen_status = Order::where('type',Order::ORDER)->where('waiter_id',$auth_waiter->id)->whereIn('status',[Order::KITCHEN_CONFIRM,Order::READYFORPICKUP,Order::WAITER_PENDING])->get();
        $data = [
            'active_tables' => $orderTbl->count() ? TableResource::collection($orderTbl) : [],
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

    public function viewCart(Request $request)
    {
        $cart_data = $this->orderRepository->getCartdataWaiter($request->all());
        if($cart_data)
        {
            return $this->respondSuccess('Cart data found', new OrderResource($cart_data));
        }

        return $this->respondWithError('Your cart is empty.');
    }

    public function orderHistory(OrderHistoryRequest $request)
    {
        $order_data = $this->orderRepository->getwaiterOrderdata($request->validated());
        if($order_data)
        {
            $data = [
                'total_order' => $order_data['total_orders'],
                'orders'      => $order_data['total_orders'] ? OrderListResource::collection($order_data['orders']) : ''
            ];
            return $this->respondSuccess('Order data found', $data);
        }
        return $this->respondWithError('Your order not found.');
    }

    public function placeOrder(PlaceOrderRequest $request)
    {
        $place_order = $this->orderRepository->placeOrderwaiter($request->validated());
        return $this->respondSuccess('Order payment successfully.', new OrderResource($place_order));
    }

    public function waiterupdateCart(Request $request)
    {
        $input = $request->all();
        $order = Order::findOrFail($input['order_id']);
        $order->loadMissing(['order_items']);

        $order = $this->orderRepository->updateCart($order,$input);
        return $this->respondSuccess('Order updated successfully',new OrderResource($order));
    }

    public function waiterPayment(Request $request)
    {
        $takePayment = $this->orderRepository->takePayment($request->all());
        return $this->respondSuccess('Payment successfully', new OrderResource(($takePayment)));
    }

    public function addCard(Request $request)
    {
        $cardDetails = $this->orderRepository->addNewCard($request->all());
        return $this->respondSuccess('New Card Added successfully,',$cardDetails);
    }

    public function tableList()
    {
        $orderTable = $this->orderRepository->tableOrderLists();
        return $this->respondSuccess('Table List successfully', OrderListResource::collection(($orderTable)));
    }

    public function addCusToTbl(Request $request)
    {
        $CusToTbl = $this->orderRepository->customerTable($request->all());
        return $this->respondSuccess("Table Allocated Successfully", $CusToTbl);
    }


}
