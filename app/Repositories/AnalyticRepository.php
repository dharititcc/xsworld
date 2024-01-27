<?php namespace App\Repositories;

use App\Models\CustomerTable;
use App\Models\Item;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Restaurant;
use App\Models\RestaurantItem;
use App\Models\RestaurantTable;
use App\Repositories\BaseRepository;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
     * @return \Illuminate\Database\Eloquent\Collection | Void
     */
    public function getAnalyticsTableData(Restaurant $restaurant , Array $data)
    {
        $cate_id = NULL;
        if(!empty($input['category']))
        {
            $cate_id = $input['category'];
        }
        return  OrderItem::select([
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
                AND orders.status = ".Order::CONFIRM_PICKUP."
                AND orders.restaurant_id = {$restaurant->id}
                GROUP BY restaurant_item_id, variation_id
            ) AS `tmp`
        "), function($join)
        {
            $join->on('order_items.id', '=', 'tmp.order_item_id');
        })
        ->leftJoin('restaurant_item_variations', 'restaurant_item_variations.id', '=', 'order_items.variation_id')
        ->whereHas('order', function($query) use($restaurant){
            $query->select('*');
            $query->select('type AS order_type');
            $query->where('restaurant_id', $restaurant->id);
            $query->where('status', Order::CONFIRM_PICKUP);
        })
        // ->item()
        ->join('restaurant_items', function($query)
        {
            $query->on('restaurant_items.id', '=', 'order_items.restaurant_item_id');
        })
        ->where('restaurant_items.type', RestaurantItem::ITEM)
        // ->where('restaurant_items.category_id', $cate_id)
        ->where(function($query)
        {
            $query->whereRaw("DATE(`order_items`.`created_at`) BETWEEN '2024-01-01' AND '2024-01-31'");
        })
        ->groupBy(['order_items.restaurant_item_id', 'order_items.variation_id'])
        // echo common()->formatSql($items);die;
        ->get();
        // return $items->get();
    }

    /**
     * Method getChart
     *
     * @param Restaurant $restaurant [explicite description]
     * @param array $data [explicite description]
     *
     * @return array
     */
    public function getChart(Restaurant $restaurant,array $data ): array
    {
        $restaurant->loadMissing(['sub_categories']);

        $startDate  = isset($data['start_date']) ? $data['start_date'] : Carbon::now()->startOfMonth()->toDateString();
        $endDate    = isset($data['end_date']) ? $data['end_date'] : Carbon::now()->endOfMonth()->toDateString();

        $dates      = get_dates_period($startDate, $endDate);
        $newDates   = array_map(function($date)
        {
            return $date->format('Y-m-d');
        }, $dates);
        $newData    = [];

        // get categories pluck
        $categories = $restaurant->categories()->with(['children_parent'])->whereNotNull('parent_id')->get();
        // dd($categories);
        foreach( $categories as $kCat => $category )
        {
            $newData[$kCat]['name'] = $category->name;
            $total = [];
            if( !empty( $newDates ) )
            {
                foreach( $newDates as $kDate => $date )
                {
                    $dates = [];
                    $result = DB::select(
                        "SELECT
                            categories.name,
                            SUM(order_items.total) AS total_order_txn,
                            SUM(orders.total) AS total_order_txn,
                            DATE(order_items.created_at) AS order_date
                        FROM categories
                        RIGHT JOIN restaurant_items on restaurant_items.category_id = categories.id
                        RIGHT JOIN order_items ON order_items.restaurant_item_id = restaurant_items.id
                        RIGHT JOIN orders ON orders.id = order_items.order_id
                        where restaurant_items.type = ".Item::ITEM."
                        AND orders.status = ".Order::CONFIRM_PICKUP."
                        AND categories.restaurant_id = {$restaurant->id}
                        AND categories.id = {$category->id}
                        AND categories.deleted_at IS NULL
                        AND restaurant_items.deleted_at IS NULL
                        AND DATE(order_items.created_at) = '{$date}'
                        GROUP BY categories.id, DATE(order_items.created_at)"
                    );
                    if( !empty( $result ) )
                    {
                        if( isset( $newData[$kCat]['name'] ) && $newData[$kCat]['name'] == $category->name )
                        {
                            $total[] = (float) $result[0]->total_order_txn;
                        }
                    }
                    else
                    {
                        if( isset( $newData[$kCat]['name'] ) && $newData[$kCat]['name'] == $category->name )
                        {
                            $total[] = 0;
                        }
                    }

                    $dates[] = $date;
                }
            }

            $newData[$kCat]['data']     = $total;
        }
        return ['data' => $newData, 'dates' => $newDates];
    }

    public function getKeyInsights($id)
    {
        $total_tbl           = RestaurantTable::where('restaurant_id',$id)->get();
        $active_tbl          = RestaurantTable::where(['restaurant_id' => $id, 'status' =>RestaurantTable::ACTIVE])->count();
        $restaurant_table    = RestaurantTable::where('restaurant_id',$id)->pluck('id')->toArray();
        $occupied_tbl        = CustomerTable::whereIn('restaurant_table_id',$restaurant_table)
                                        ->groupBy('waiter_id') ->select('id', DB::raw('count(*) as count'))
                                        ->get();
        $keyInsights = [
            'total_tables'  => $total_tbl->count(),
            'active_tbl'    => $active_tbl,
            'occupied_tbl'  => $occupied_tbl->count()
        ];

        return $keyInsights;
    }
}