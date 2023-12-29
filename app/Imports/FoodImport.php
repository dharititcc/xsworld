<?php

namespace App\Imports;

use App\Models\RestaurantItem;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;

class FoodImport implements ToCollection , WithStartRow
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        return new RestaurantItem([
            'name' => $collection[0],
            'product_type' => $collection[0],
            // Add more columns as needed
        ]);
    }


     /**
     * @return int
     */
    public function startRow(): int
    {
        return 2;
    }
}
