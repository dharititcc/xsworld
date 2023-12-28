<?php

namespace App\Imports;

use App\Models\RestaurantTime;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;

class DrinkImport implements ToCollection,WithStartRow
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        dd($collection);
        return new RestaurantTime([
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
