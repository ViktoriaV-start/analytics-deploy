<?php

namespace App\Services;

use App\Models\Income;
use App\Models\Order;
use App\Models\Sale;

class DbService
{
    /**
     * Save data to DB.
     */
    public function saveToDb(array $data, string $section) {

        foreach ($data as $item) {
            $itemArr = (array) $item;

            match ($section) {
                'orders' => (new Order())->fill($itemArr)->save(),
                'incomes' => (new Income())->fill($itemArr)->save(),
                'sales' => (new Sale())->fill($itemArr)->save(),
            };
        }
    }
}
