<?php namespace App\Repositories\Traits;

use App\Billing\Stripe;
use App\Exceptions\GeneralException;
use App\Http\Controllers\Api\V1\Traits\OrderStatus;
use App\Mail\InvoiceMail;
use App\Models\Category;
use App\Models\CustomerTable;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderSplit;
use App\Models\Restaurant;
use App\Models\RestaurantItem;
use App\Models\RestaurantPickupPoint;
use App\Models\RestaurantTable;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

trait OrderFlow
{
    use OrderStatus, XSNotifications;
    /**
     * Method addTocart
     *
     * @param array $data [explicite description]
     *
     * @return mixed
     */
    public function addTocart(array $data)
    {
        $user       = isset( $data['order']['user_id'] ) ? User::findOrFail($data['order']['user_id']) : auth()->user();
        $order      = isset( $data['order'] ) ? $data['order'] : [];
        $orderItems = isset( $data['order_items'] ) ? $data['order_items'] : [];

        $user->loadMissing(['latest_cart', 'latest_cart.restaurant']);

        $latestCart = $user->latest_cart;

        if( isset( $latestCart->id ) && ($latestCart->restaurant->id ==  $order['restaurant_id']) )
        {
            // check restaurant id available in the cart
            return $this->checkSameRestaurantOrder($user, $latestCart, $orderItems);
        }
        else
        {
            // new order
            return $this->createOrder($user, $order, $orderItems);
        }

        throw new GeneralException('Order request is invalid.');
    }

    /**
     * Method checkSameRestaurantOrder
     *
     * @param User $user [explicite description]
     * @param Order $order [explicite description]
     * @param array $orderItems [explicite description]
     *
     * @return Order
     */
    public function checkSameRestaurantOrder(User $user, Order $order, array $orderItems): Order
    {
        $isFoodAvailable    = 0;
        $parentCategory     = null;
        // check if order request is available
        if( isset( $order->id ) && !empty($orderItems) )
        {
            if( !empty($orderItems) )
            {
                foreach( $orderItems as $item )
                {
                    $category           = Category::with(['children_parent'])->find($item['category_id']);
                    $parentCategory     = $category->parent_id;
                    $isFoodAvailable    = $category->children_parent->name == 'Food' ? 1 : 0;

                    // order split create
                    $checkExistOrderSplit = $order->order_splits()->where('is_food', $isFoodAvailable)->first();

                    if( !isset($checkExistOrderSplit->id) )
                    {
                        // create order split row
                        $checkExistOrderSplit = $this->createOrderSplit([
                            'order_id'      => $order->id,
                            'is_food'       => $isFoodAvailable,
                            'status'        => OrderSplit::PENDING
                        ]);
                    }

                    // make proper item array for the table
                    $itemArr = [
                        'restaurant_item_id'    => $item['item_id'],
                        'category_id'           => $parentCategory,
                        'order_split_id'        => $checkExistOrderSplit->id,
                        'price'                 => $item['price'],
                        'quantity'              => $item['quantity'],
                        'type'                  => RestaurantItem::ITEM,
                        'total'                 => $item['quantity'] * $item['price']
                    ];

                    if( isset( $item['variation'] ) && !empty( $item['variation'] ) )
                    {
                        $variationArr = [
                            'restaurant_item_id'    => $item['item_id'],
                            'category_id'           => $parentCategory,
                            'order_split_id'        => $checkExistOrderSplit->id,
                            'parent_item_id'        => null,
                            'variation_id'          => $item['variation']['id'],
                            'quantity'              => $item['variation']['quantity'],
                            'price'                 => $item['variation']['price'],
                            'type'                  => RestaurantItem::ITEM,
                            'total'                 => $item['variation']['price'] * $item['variation']['quantity']
                        ];

                        if( (isset( $item['mixer'] ) && !empty( $item['mixer'] )) || (isset( $item['addons'] ) && !empty( $item['addons'] )) )
                        {
                            $newOrderItem = $this->createOrderItem($order, $variationArr);
                        }
                        else
                        {
                            // check same variation exist
                            $newOrderItem = $this->checkSameVariationItemExist($order, $item['item_id'], $item['variation']['id']);
                            if( isset( $newOrderItem->id ) )
                            {
                                // update variation quantity and total of that item
                                $variationQuantity = $newOrderItem->quantity + $item['variation']['quantity'];
                                $variationTotal    = $variationQuantity * $item['variation']['price'];
                                $newOrderItem->update(['quantity' => $variationQuantity, 'total' => $variationTotal]);
                            }
                            else
                            {
                                // add variation in the order items table
                                $newOrderItem = $this->createOrderItem($order, $variationArr);
                            }
                        }
                    }
                    else
                    {
                        // check same item exist
                        $newOrderItem = $this->checkSameItemExist($order, $item['item_id']);

                        if( isset( $newOrderItem->id ) )
                        {
                            // update item quantity and total of that item
                            $itemQuantity = $newOrderItem->quantity + $item['quantity'];
                            $itemTotal    = $itemQuantity * $item['price'];
                            $newOrderItem->update(['quantity' => $itemQuantity, 'total' => $itemTotal]);
                        }
                        else
                        {
                            $newOrderItem = $this->createOrderItem($order, $itemArr);
                        }
                    }

                    // make proper mixer data for the table
                    if( isset( $item['mixer'] ) && !empty( $item['mixer'] ) )
                    {
                        $mixerArr = [
                            'restaurant_item_id'=> $item['mixer']['id'],
                            'order_split_id'    => $checkExistOrderSplit->id,
                            'parent_item_id'    => $newOrderItem->id,
                            'price'             => $item['mixer']['price'],
                            'type'              => RestaurantItem::MIXER,
                            'quantity'          => $item['mixer']['quantity'],
                            'total'             => $item['mixer']['quantity'] * $item['mixer']['price']
                        ];

                        $mixerItem = $this->createOrderItem($order, $mixerArr);
                    }

                    // make proper data for addons
                    if( isset( $item['addons'] ) && !empty( $item['addons'] ) )
                    {
                        $addons = $item['addons'];

                        if( !empty( $addons ) )
                        {
                            foreach( $addons as $addon )
                            {
                                $addonData = [
                                    'order_split_id'        => $checkExistOrderSplit->id,
                                    'restaurant_item_id'    => $addon['id'],
                                    'parent_item_id'        => $newOrderItem->id,
                                    'price'                 => $addon['price'],
                                    'type'                  => RestaurantItem::ADDON,
                                    'quantity'              => $addon['quantity'],
                                    'total'                 => $addon['quantity'] * $addon['price']
                                ];

                                $addonItem = $this->createOrderItem($order, $addonData);
                            }
                        }
                    }
                }
            }

            $order->refresh();
            $order_category_type = $this->checkOrderCategoryType($order->order_items);
            $order->loadMissing(['items']);
            $order->update(['total' => $order->items->sum('total'),'order_category_type' => $order_category_type]);

            return $order;
        }

        throw new GeneralException('Order could not be updated.');
    }

    /**
     * Method checkSameItemExist
     *
     * @param Order $order [explicite description]
     * @param mixed $itemId [explicite description]
     *
     * @return null|OrderItem
     */
    public function checkSameItemExist(Order $order, $itemId): ?OrderItem
    {
        // check first item or variation
        return $order->order_items()->where('restaurant_item_id', $itemId)->first();
    }

    /**
     * Method checkSameVariationItemExist
     *
     * @param Order $order [explicite description]
     * @param int $parentItem [explicite description]
     * @param mixed $itemId [explicite description]
     *
     * @return null|OrderItem
     */
    public function checkSameVariationItemExist(Order $order, int $parentItem, $itemId): ?OrderItem
    {
        // check first item or variation
        $orderItemVariation = $order->order_items()->with(['addons', 'mixer'])->where('restaurant_item_id', $parentItem)->where('variation_id', $itemId)->whereNull('parent_item_id')->first();

        if( isset( $orderItemVariation->id ) )
        {
            if( $orderItemVariation->addons->count() )
            {
                return null;
            }

            if( isset($orderItemVariation->mixer->id) )
            {
                return null;
            }

            if( (!isset($orderItemVariation->mixer->id)) || ($orderItemVariation->addons->count() === 0) )
            {
                return $orderItemVariation;
            }
        }
        return null;
    }

    /**
     * Method checkSameMixerExistInParent
     *
     * @param Order $order [explicite description]
     * @param int $parentId [explicite description]
     * @param mixed $itemId [explicite description]
     *
     * @return null|OrderItem
     */
    public function checkSameMixerExistInParent(Order $order, $parentId, $itemId): ?OrderItem
    {
        // check first item or variation
        return $order->order_mixer()->where('restaurant_item_id', $itemId)->where('parent_item_id', $parentId)->first();
    }

    /**
     * Method checkSameAddonsExistInParent
     *
     * @param Order $order [explicite description]
     * @param int $parentId [explicite description]
     * @param mixed $itemId [explicite description]
     *
     * @return null|OrderItem
     */
    public function checkSameAddonsExistInParent(Order $order, $parentId, $itemId): ?OrderItem
    {
        // check first item or variation
        return $order->order_addons()->where('restaurant_item_id', $itemId)->where('parent_item_id', $parentId)->first();
    }

    /**
     * Method createOrderSplit
     *
     * @param array $data [explicite description]
     *
     * @return OrderSplit
     */
    public function createOrderSplit(array $data): OrderSplit
    {
        return OrderSplit::create($data);
    }

    /**
     * Method createOrder
     *
     * @param User $user [explicite description]
     * @param array $data [explicite description]
     * @param array $orderItems [explicite description]
     *
     * @return Order
     */
    public function createOrder(User $user, array $data, array $orderItems): Order
    {
        $restaurant                     = Restaurant::find($data['restaurant_id']);
        $order['user_id']               = $user->id;
        $order['restaurant_id']         = $restaurant->id;
        $order['currency_id']           = $restaurant->currency_id;
        $order['waiter_id']             = access()->isWaiter() ? auth()->user()->id : null;
        $order['restaurant_table_id']   = isset($data['restaurant_table_id']) ? $data['restaurant_table_id'] : null;
        $order['status']                = Order::PENDNIG;
        $order['waiter_status']         = Order::CURRENTLY_BEING_PREPARED;

        $newOrder = Order::create($order);

        $newOrder->refresh();

        if($order['restaurant_table_id']) {
            CustomerTable::where('user_id', $user->id)->where('restaurant_table_id', $order['restaurant_table_id'])->whereNull('order_id')->update(['order_id' => $newOrder->id]);
        }

        return $this->checkSameRestaurantOrder($user, $newOrder, $orderItems);
    }

    /**
     * Method updateCart
     *
     * @param Order $order [explicite description]
     * @param array $data [explicite description]
     *
     * @return Order
     */
    public function updateCart(Order $order, array $data): Order
    {
        /*[
            "order_id" => 10
            "item_id" => 38
            "quantity" => 5
        ];*/
        $user       = auth()->user();
        $orderItem  = $order->order_items->where('id', $data['item_id'])->first();

        $orderItem->loadMissing(['addons', 'mixer']);

        // update order item quantity
        $orderItem->update(['quantity' => $data['quantity'], 'total' => $data['quantity'] * $orderItem->price]);

        // check if that item has any addons
        if( $orderItem->addons->count() )
        {
            foreach( $orderItem->addons as $addon )
            {
                $addon->update(['quantity' => $data['quantity'], 'total' => $data['quantity'] * $addon->price]);
            }
        }

        // check if that item has any mixer
        if( isset( $orderItem->mixer->id ) )
        {
            $orderItem->mixer->update(['quantity' => $data['quantity'], 'total' => $data['quantity'] * $orderItem->mixer->price]);
        }

        $order->refresh();
        $order->loadMissing(['items']);
        $order->update(['total' => $order->items->sum('total')]);

        return $order;
    }

    /**
     * Method createOrderItem
     *
     * @param Order $order [explicite description]
     * @param array $data [explicite description]
     *
     * @return OrderItem
     */
    public function createOrderItem(Order $order, array $data): OrderItem
    {
        return $order->items()->create($data);
    }

    /**
     * Method checkOrderCategoryType
     *
     * @param Collection $items [explicite description]
     *
     * @return int
     */
    public function checkOrderCategoryType(Collection $items): int
    {
        $isOrderCategoryType = 0;
        $isDrinkCategory = 0;
        $isFoodCategory = 0;
        if( $items->count() )
        {
            foreach( $items as $item )
            {
                $item->loadMissing(['restaurant_item', 'restaurant_item.category.children_parent']);

                $parentCategory = $item->restaurant_item->category->children_parent;

                if( $parentCategory->name == 'Food' )
                {
                    $isFoodCategory = 1;
                }

                if( $parentCategory->name == 'Drinks' )
                {
                    $isDrinkCategory = 1;
                }
            }
        }

        if( $isDrinkCategory == 1 && $isFoodCategory == 1 )
        {
            $isOrderCategoryType = 2;
        }
        else if( $isDrinkCategory !== 1 && $isFoodCategory === 1 )
        {
            $isOrderCategoryType = 1;
        }
        else
        {
            $isOrderCategoryType = 0;
        }

        return $isOrderCategoryType;
    }

    /**
     * Method placeOrder
     *
     * @param array $data [explicite description]
     *
     * @return bool
     */
    function placeOrder(array $data): bool
    {
        $orderIdArr           = [];
        $card_id            = $data['card_id'] ?? null;
        $credit_amount      = isset($data['credit_amount']) && $data['credit_amount'] != '' ? $data['credit_amount'] : 0;
        $amount             = $data['amount'] ? $data['amount'] : 0;
        $table_id           = $data['table_id'] ? $data['table_id'] : null;
        $order              = Order::with([
            'restaurant',
            'restaurant.kitchens',
            'order_splits',
            'order_split_food',
            'order_split_drink'
        ])->findOrFail($data['order_id']);
        $user               = $order->user_id ? User::findOrFail($order->user_id) : auth()->user();
        $getcusTbl          = CustomerTable::where('user_id' , $user->id)->where('restaurant_table_id', $table_id)->where('order_id', $order->id)->first();
        $isTableActive      = RestaurantTable::withTrashed()->where('id', $table_id)->first();
        $pickup_point_id    = '';

        // check if qr is enable or not
        if( isset($isTableActive->id) )
        {
            if( $isTableActive->deleted_at )
            {
                throw new GeneralException('You cannot place order as QR is deleted.');
            }

            if( $isTableActive->status == 0 )
            {
                throw new GeneralException('You cannot place order as QR is disabled.');
            }
        }

        // if( isset( $getcusTbl->id ) )
        // {
        //     throw new GeneralException('Already table allocated to this Customer');
        // }

        if( isset($order->order_split_food->id) )
        {
            if( $order->restaurant->kitchens->count() )
            {
                $openKitchens = $order->restaurant->kitchens()->where('status', 1)->get();
                if( $openKitchens->count() === 0 )
                {
                    throw new GeneralException('You cannot able to place order as kitchen is closed.');
                }
            }
            else
            {
                throw new GeneralException('You cannot able to place order as there is no kitchen found.');
            }
        }

        // check if order if of category type both or single(food/drink)
        if( $order->order_category_type == Order::BOTH )
        {
            $pickup_point_id    = $this->randomPickpickPoint($order);

            // check order split count > 1
            if( $order->order_splits->count() > 1 )
            {
                foreach( $order->order_splits as $key => $split )
                {
                    $latest = null;
                    $split->loadMissing(['items']);

                    if( $key === 0 )
                    {
                        $orderArr = [
                            'user_id'               => $user->id,
                            'order_category_type'   => $split->is_food == 1 ? Order::FOOD : Order::DRINK,
                            'restaurant_id'         => $order->restaurant_id,
                            'pickup_point_id'       => $split->is_food == 0 ? $pickup_point_id->id : null,
                            'pickup_point_user_id'  => $split->is_food == 0 ? $pickup_point_id->user_id : null,
                            'restaurant_table_id'   => isset($table_id) ? $table_id : null,
                            'status'                => Order::PENDNIG,
                            'waiter_status'         => Order::CURRENTLY_BEING_PREPARED,
                            'currency_id'           => $order->restaurant->currency_id,
                            'place_at'              => Carbon::now(),
                        ];

                        $order->update($orderArr);

                        $split->update(['order_id' => $order->id]);
                        $split->items()->update(['order_id' => $order->id]);
                        $order->refresh();

                        // update total of the order by items
                        $order->loadMissing(['items']);
                        $order->update(['total' => $split->all_items->sum('total')]);

                        $latest = Order::with([
                            'restaurant',
                            'restaurant.kitchens',
                            'order_splits',
                            'order_split_food',
                            'order_split_drink'
                        ])->find($order->id);

                        $orderIdArr[] = $latest->id;
                    }
                    else
                    {
                        $orderArr = [
                            'user_id'               => $user->id,
                            'order_category_type'   => $split->is_food == 1 ? Order::FOOD : Order::DRINK,
                            'restaurant_id'         => $order->restaurant_id,
                            'pickup_point_id'       => $split->is_food == 0 ? $pickup_point_id->id : null,
                            'pickup_point_user_id'  => $split->is_food == 0 ? $pickup_point_id->user_id : null,
                            'restaurant_table_id'   => isset($table_id) ? $table_id : null,
                            'type'                  => Order::ORDER,
                            'status'                => Order::PENDNIG,
                            'waiter_status'         => Order::CURRENTLY_BEING_PREPARED,
                            'currency_id'           => $order->restaurant->currency_id,
                            'place_at'              => Carbon::now(),
                        ];

                        $order = Order::create($orderArr);

                        $split->update(['order_id' => $order->id]);
                        $split->items()->update(['order_id' => $order->id]);

                        $order->refresh();

                        // update total of the order by items
                        $order->loadMissing(['items']);
                        $order->update(['total' => $split->all_items->sum('total')]);

                        $latest = Order::with([
                            'restaurant',
                            'restaurant.kitchens',
                            'order_splits',
                            'order_split_food',
                            'order_split_drink'
                        ])->find($order->id);

                        $orderIdArr[] = $latest->id;
                    }

                    $creditAmountSplit = 0;
                    if($order->total <= $credit_amount)
                    {
                        $creditAmountSplit  = $order->total;
                        $amount             = 0;
                        $credit_amount     -= $creditAmountSplit;
                    }
                    else if( $order->total > $credit_amount )
                    {
                        $creditAmountSplit  = $credit_amount;
                        $amount             = $order->total - $creditAmountSplit;
                        $credit_amount     -= $creditAmountSplit;
                    }
                    $latest->refresh();

                    // charge payment
                    $this->getOrderPayment($latest, $user, $creditAmountSplit, $amount, $card_id);
                    $latest->refresh();

                    $getcusTbl = CustomerTable::where('user_id', $user->id)->where('restaurant_table_id', $table_id)->where('order_id', $latest->id)->first();
                    if($getcusTbl) {
                        throw new GeneralException('Already table allocated');
                        $customerTbl = 0;
                    } else {
                        $customerTbl = CustomerTable::updateOrCreate([
                            'restaurant_table_id'   => $table_id,
                            'user_id'               => $user->id,
                            'order_id'              => $latest->id,
                        ]);
                    }

                    // send notification to kitchens of the restaurant if order is food
                    if( isset($latest->order_split_food->id) )
                    {
                        $kitchenTitle    = 'New order placed by customer';
                        $kitchenMessage  = "Order is #{$latest->id} placed by customer";
                        $this->notifyKitchens($latest, $kitchenTitle, $kitchenMessage);
                    }

                    // send waiter notification if table is selected
                    if( isset( $table_id ) )
                    {
                        $this->sendWaiterNotification($latest, $user, $table_id);
                    }

                    // customer notification
                    $text               = $latest->restaurant->name. ' is processing your order';
                    $title              = $text;
                    $message            = "Your Order is #".$latest->id." placed";

                    $this->notifyCustomer($latest, $title, $message);

                    // send notification to bar of the restaurant if order is drink
                    if( isset($latest->order_split_drink->id) )
                    {
                        $bartitle           = "Order is placed by Customer";
                        $barmessage         = "Order is #".$latest->id." placed by customer";
                        $this->notifyBars($latest, $bartitle, $barmessage);
                    }
                }
            }
        }
        else
        {
            $pickup_point_id            = isset($data['pickup_point_id']) ? RestaurantPickupPoint::findOrFail($data['pickup_point_id']) : null;
            $updateArr                  = [];

            if(isset($order->id))
            {
                $updateArr = [
                    'user_id'               => $user->id,
                    'restaurant_id'         => $order->restaurant_id,
                    'pickup_point_id'       => isset($order->order_split_drink->id) && isset( $pickup_point_id->id ) ? $pickup_point_id->id : null,
                    'pickup_point_user_id'  => isset($order->order_split_drink->id) && isset( $pickup_point_id->id ) ? $pickup_point_id->user_id : null,
                    'restaurant_table_id'   => isset($table_id) ? $table_id : null,
                ];
                $order->update($updateArr);

                $creditAmountSplit = 0;
                if($order->total <= $credit_amount)
                {
                    $creditAmountSplit  = $order->total;
                    $amount             = 0;
                    $credit_amount     -= $creditAmountSplit;
                }
                else if( $order->total > $credit_amount )
                {
                    $creditAmountSplit  = $credit_amount;
                    $amount             = $order->total - $creditAmountSplit;
                    $credit_amount     -= $creditAmountSplit;
                }
                $this->getOrderPayment($order, $user, $creditAmountSplit, $amount, $card_id);
            }

            $order->refresh();
            $order->loadMissing(['items']);

            $orderIdArr[] = $order->id;

            $getcusTbl = CustomerTable::where('user_id', $user->id)->where('restaurant_table_id', $table_id)->where('order_id', $order->id)->first();
            if($getcusTbl) {
                throw new GeneralException('Already table allocated');
                $customerTbl = 0;
            } else {
                $customerTbl = CustomerTable::updateOrCreate([
                    'restaurant_table_id'   => $table_id,
                    'user_id'               => $user->id,
                    'order_id'              => $order->id,
                ]);
            }

            // send notification to waiter if table order
            if( isset( $table_id ) )
            {
                $this->sendWaiterNotification($order, $user, $table_id);
            }

            $order->refresh();

            // send notification to kitchens of the restaurant if order is food
            if( isset($order->order_split_food->id) )
            {
                $kitchenTitle    = 'New order placed by customer';
                $kitchenMessage  = "Order is #{$order->id} placed by customer";
                $this->notifyKitchens($order, $kitchenTitle, $kitchenMessage);
            }

            // customer notification
            $text               = $order->restaurant->name. ' is processing your order';
            $title              = $text;
            $message            = "Your Order is #".$order->id." placed";

            $this->notifyCustomer($order, $title, $message);

            // send notification to bar of the restaurant if order is drink
            if( isset($order->order_split_drink->id) )
            {
                $bartitle           = "Order is placed by Customer";
                $barmessage         = "Order is #".$order->id." placed by customer";
                $this->notifyBars($order, $bartitle, $barmessage);
            }
        }
        // Generate PDF
        $this->generatePDF($orderIdArr);

        // take payment
        $this->captureKitchenCharge($orderIdArr);

        return true;
    }

    /**
     * Method captureKitchenCharge
     *
     * @param array $orders [explicite description]
     *
     * @return void
     */
    public function captureKitchenCharge(array $orders)
    {
        if( !empty( $orders ) )
        {
            foreach( $orders as $order )
            {
                $order = Order::find($order);
                $order->loadMissing([
                    'restaurant',
                    'order_split_food',
                    'order_split_drink'
                ]);

                if( isset( $order->order_split_food->id ) )
                {
                    if( $order->charge_id )
                    {
                        $stripe                         = new Stripe();
                        $payment_data                   = $stripe->captureCharge($order->charge_id);
                        $transactionId                  = $payment_data->balance_transaction;

                        $order->update(['transaction_id' => $transactionId]);
                    }
                }
            }
        }
    }

    /**
     * Method getOrderPayment
     *
     * @param Order $order [explicite description]
     * @param User $user [explicite description]
     * @param mixed $credit_amount [explicite description]
     * @param float $amount [explicite description]
     * @param mixed $card_id [explicite description]
     *
     * @return void
     */
    public function getOrderPayment(Order $order, User $user, mixed $credit_amount, float $amount, $card_id)
    {
        $paymentArr                 = [];
        $userCreditAmountBalance    = $user->credit_amount;
        $credit_amount              = isset( $credit_amount ) ? $credit_amount : 0;

        if( $amount > 0 )
        {
            $paymentArr = [
                'amount'        => number_format($amount, 2) * 100,
                'currency'      => $order->restaurant->currency->code,
                'customer'      => $user->stripe_customer_id,
                'capture'       => false,
                'source'        => $card_id,
                'description'   => $order->id
            ];

            $stripe         = new Stripe();
            $payment_data   = $stripe->createCharge($paymentArr);
        }

        $updateArr = [
            'type'                  => Order::ORDER,
            'card_id'               => $card_id,
            'charge_id'             => $payment_data->id ?? null,
            'credit_amount'         => $credit_amount,
            'amount'                => $amount,
            'place_at'              => Carbon::now(),
        ];

        $remaingAmount = $userCreditAmountBalance - $credit_amount;

        // update user's credit amount
        $this->updateUserPoints($user, ['credit_amount' => $remaingAmount]);

        $order->update($updateArr);
    }

    /**
     * Method sendWaiterNotification
     *
     * @param Order $order [explicite description]
     * @param User $user [explicite description]
     * @param int $table_id [explicite description]
     *
     * @return void
     */
    public function sendWaiterNotification(Order $order, User $user, int $table_id)
    {
        // send notification to waiter if table order
        $order->loadMissing([
            'restaurant',
            'restaurant.waiters'
        ]);

        // send notification to waiters of the restaurant
        $waiterTitle    = 'New order placed by customer';
        $waiterMessage  = "Order is #{$order->id} placed by customer";
        $code           = Order::WAITER_NEW_ORDER;
        $this->notifyWaiters($order, $waiterTitle, $waiterMessage, $code);
    }

    /**
     * Method randomPickpickPoint
     *
     * @param Order $order [explicite description]
     *
     * @return null|RestaurantPickupPoint
     */
    public function randomPickpickPoint(Order $order): ?RestaurantPickupPoint
    {
        $restaurant_id = $order->restaurant_id;
        $pickup_point_id = RestaurantPickupPoint::where(['restaurant_id' => $restaurant_id, 'status' => RestaurantPickupPoint::ONLINE, 'is_table_order' => 1])->inRandomOrder()->first();
        return $pickup_point_id;
    }

    /**
     * Method getKitchenConfirmedOrders
     *
     * @return Collection
     */
    public function getKitchenConfirmedOrders(): Collection
    {
        $kitchen = auth()->user();

        // load restaurant relationship
        $kitchen->loadMissing(['restaurant_kitchen']);

        $query = $this->getKitchenOrdersQuery($kitchen, OrderSplit::PENDING, 'desc')->whereIn('status', [Order::PENDNIG, Order::ACCEPTED])->where('type', Order::ORDER);
        return $query->get();
    }

    /**
     * Method getKitchenOrdersQuery
     *
     * @param User $kitchen [explicite description]
     * @param mixed $status [explicite description]
     * @param string $sort [explicite description]
     *
     * @return Builder
     */
    public function getKitchenOrdersQuery(User $kitchen, $status, string $sort = 'asc'): Builder
    {
        return Order::query()
                        ->with(
                            [
                                'restaurant',
                                'restaurant_table',
                                'restaurant.country',
                                'restaurant.currency',
                                'user',
                                'user.attachment',
                                'restaurant_pickup_point',
                                'pickup_point_user',
                                'pickup_point_user.attachment',
                                'restaurant_pickup_point.attachment',
                                'order_split_food',
                                'order_split_food.items',
                                'order_split_food.items.restaurant_item',
                            ]
                        )
                        ->whereHas('order_split_food', function($query) use($status){
                            if( is_array($status) )
                            {
                                $query->whereIn('status', $status);
                            }
                            else
                            {
                                $query->whereIn('status', [$status]);
                            }
                        })
                        ->where('restaurant_id', $kitchen->restaurant_kitchen->restaurant_id)
                        ->orderBy('id', $sort);
    }

    /**
     * Method getKitchenOrderCollections
     *
     * @return Collection
     */
    public function getKitchenOrderCollections(): Collection
    {
        $kitchen = auth()->user();

        // load restaurant relationship
        $kitchen->loadMissing(['restaurant_kitchen']);

        $query = $this->getKitchenOrdersQuery($kitchen, OrderSplit::READYFORPICKUP, 'asc');
        return $query->get();
    }

    /**
     * Method getCompletedKitchenOrders
     *
     * @return array
     */
    public function getCompletedKitchenOrders(array $data): array
    {
        $kitchen = auth()->user();
        
        $page   = isset($data['page']) ? $data['page'] : 1;
        $limit  = isset($data['limit']) ? $data['limit'] : 10;
        // load restaurant relationship
        $kitchen->loadMissing(['restaurant_kitchen']);

        $query = $this->getKitchenOrdersQuery($kitchen, [OrderSplit::KITCHEN_CONFIRM, OrderSplit::KITCHEN_CANCELED],'desc');
        $total = $query->count();
        $query->limit($limit)->offset(($page - 1) * $limit)->orderBy('id', 'desc');
        $query = $query->get();
        if( $query->count() )
        {
            $orderData = [
                'total_orders'   => $total,
                'orders'         => $query
            ];

            return $orderData;
        }

        throw new GeneralException('There is no order found.');
        // return $query;
    }

    /**
     * Method generatePDF
     *
     * @param Order $order [explicite description]
     *
     * @return mixed
     */
    public function generatePDF(array $orderArr)
    {
        if( !empty( $orderArr ) )
        {
            foreach($orderArr as $order)
            {
                $pdfData  = Order::with(
                    [   'order_items',
                        'restaurant',
                        'restaurant.country',
                        'restaurant.currency'
                    ])->find($order);

                $restaurant = $pdfData->restaurant->owners()->first();
                $pdf        = app('dompdf.wrapper');
                $customPaper = array(0,0,567.00,283.80);
                $pdf->loadView('pdf.index',compact('pdfData','restaurant'))->setPaper($customPaper, 'landscape');
                $filename   = 'invoice_'.$order.'.pdf';
                $content    = $pdf->output();
                $file       = storage_path("app/public/order_pdf");
                !is_dir($file) &&
                mkdir($file, 0777, true);
                $filePath = 'public/order_pdf/' . $filename;
                //Upload PDF to storage folder
                Storage::put($filePath, $content);
                $destinationPath = asset('storage/order_pdf/').'/'.$filename;
            }
        }
        return true;
    }

    /**
     * Method sendMail
     *
     * @param Order $order [explicite description]
     *
     * @return void
     */
    public function sendMail(Order $order)
    {
        $order->loadMissing([
            'user'
        ]);
        Mail::to($order->user->email)->send(new InvoiceMail($order));
    }
}