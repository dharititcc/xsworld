<?php

namespace App\Imports;

use App\Models\RestaurantItem;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class DrinksImport implements ToModel, WithStartRow, WithValidation
{

    protected $currentRow = 2;

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // dd($row);
        $this->currentRow++;
        return new RestaurantItem([
            'name'                  => $row[1],
            "price"                 => $row[3],
            "ingredients"           => $row[4],
            "country_of_origin"     => $row[5],
            "year_of_production"    => $row[6],
            "type_of_drink"         => $row[7],
            "description"           => $row[8],
            "is_available"          => $row[9],
            "is_featured"           => $row[10],
            "is_variable"           => $row[11],
            "type"                  => RestaurantItem::ITEM,
            "restaurant_id"         => 10,
            "catgory_id"            => 20,
            // "created_at"            => Carbon::now(),
            // "updated_at"            => Carbon::now(),
        ]);
    }

    /**
     * Method startRow
     *
     * @return int
     */
    public function startRow(): int
    {
        return 2;
    }

    /**
     * Method rules
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            '1'     => 'required', // Product name
            '2'     => 'required', // Category
            '3'     => 'required|numeric', // Price
            '4'     => 'required', // ingredients
            '5'     => 'required', // Country of origin
            '6'     => 'required', // Year of production
            '7'     => 'required', // type of drink
            '8'     => 'required', // description
            '9'     => 'required|integer|between:0,1', // is_available
            '10'    => 'required|integer|between:0,1', // is_featured
            '11'    => 'required|integer|between:0,1', // is_variable
        ];
    }

    /**
     * Method customValidationMessages
     *
     * @return void
     */
    public function customValidationMessages()
    {
        return [
            '1.required'    => "The Product name field is required in row {$this->currentRow}.",
            '2.required'    => "The category field is required in row {$this->currentRow}.",

            '3.required'    => "The price field is required in row {$this->currentRow}.",
            '3.numeric'     => "The price must be a numeric value in row {$this->currentRow}.",

            '4.required'    => "The price field is required in row {$this->currentRow}.",
            '5.required'    => "The country of origin field is required in row {$this->currentRow}.",
            '6.required'    => "The year of production field is required in row {$this->currentRow}.",
            '7.required'    => "The type of drink field is required in row {$this->currentRow}.",
            '8.required'    => "The description field is required in row {$this->currentRow}.",

            '9.required'    => "The is_available field is required in row {$this->currentRow}.",
            '9.integer'     => "The is_available must be an integer in row {$this->currentRow}.",
            '9.between'     => "The is_available must be between :min and :max in row {$this->currentRow}.",

            '10.required'   => "The is_featured field is required in row {$this->currentRow}.",
            '10.integer'    => "The is_featured must be an integer in row {$this->currentRow}.",
            '10.between'    => "The is_featured must be between :min and :max in row {$this->currentRow}.",

            '11.required'   => "The is_featured field is required in row {$this->currentRow}.",
            '11.integer'    => "The is_featured must be an integer in row {$this->currentRow}.",
            '11.between'    => "The is_featured must be between :min and :max in row {$this->currentRow}.",
        ];
    }
}
