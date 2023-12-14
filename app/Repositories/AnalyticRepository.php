<?php namespace App\Repositories;

use App\Models\Item;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Restaurant;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Class AnalyticRepository.
*/
class AnalyticRepository extends BaseRepository
{
    /**
    * Associated Repository Model.
    */
    const MODEL = OrderItem::class;

    /**
     * Method getAnalyticsTableData
     *
     * @param Restaurant $restaurant [explicite description]
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAnalyticsTableData(Restaurant $restaurant): Collection
    {
        return $items = OrderItem::select([
            'order_items.*',
            'restaurant_item_variations.name AS variation_name',
            DB::raw("COUNT(variation_id) AS variation_count"),
            DB::raw("SUM(quantity) AS variation_qty_sum"),
            'tmp.total_item',
            'tmp.total_quantity'
        ])
        ->with(['order', 'variation', 'order.restaurant', 'order.restaurant.country', 'restaurant_item', 'restaurant_item'])
        ->leftJoin(DB::raw("
            (
                SELECT
                    order_items.id AS `order_item_id`,
                    COUNT(order_items.id) AS total_item,
                    SUM(order_items.quantity) AS `total_quantity`
                FROM order_items
                LEFT JOIN orders ON orders.id = order_items.order_id
                WHERE variation_id IS NULL
                AND orders.restaurant_id = {$restaurant->id}
                GROUP BY restaurant_item_id, variation_id
            ) AS `tmp`
        "), function($join)
        {
            $join->on('order_items.id', '=', 'tmp.order_item_id');
        })
        ->leftJoin('restaurant_item_variations', 'restaurant_item_variations.id', '=', 'order_items.variation_id')
        ->whereHas('order', function($query) use($restaurant){
            $query->where('restaurant_id', $restaurant->id);
            $query->where('status', Order::CONFIRM_PICKUP);
        })
        ->item()
        ->where(function($query)
        {
            $query->whereRaw("DATE(`order_items`.`created_at`) BETWEEN '2023-11-28' AND '2023-12-13'");
        })
        ->groupBy(['order_items.restaurant_item_id', 'order_items.variation_id'])
        // echo common()->formatSql($items);die;
        ->get();
    }

    /**
     * Method getChart
     *
     * @param Restaurant $restaurant [explicite description]
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getChart(Restaurant $restaurant): Collection
    {
        return OrderItem::query()
            ->select(
                [
                    'order_items.restaurant_item_id',
                    DB::raw("DATE(orders.created_at) AS order_date"),
                    DB::raw("SUM(order_items.total) AS total"),
                    'restaurant_items.category_id'
                ]
            )
            ->leftJoin('orders', 'orders.id', '=', 'order_items.order_id')
            ->leftJoin('restaurant_items', 'restaurant_items.id', '=', 'order_items.restaurant_item_id')
            ->where('orders.status', Order::CONFIRM_PICKUP)
            ->where('orders.restaurant_id', $restaurant->id)
            ->where('order_items.type', Item::ITEM)
            ->where(function($query)
            {
                $query->whereRaw("DATE(orders.created_at) BETWEEN '2023-11-15' AND '2023-12-14'");
            })
            ->groupBy('restaurant_items.category_id')
            ->get();
    }
}