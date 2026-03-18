<?php

namespace Database\Seeders;

use App\Models\Table;
use Illuminate\Database\Seeder;

class TableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tables = [
            ['table_number' => '1', 'capacity' => 2, 'status' => 'available', 'position_x' => 0, 'position_y' => 0],
            ['table_number' => '2', 'capacity' => 2, 'status' => 'available', 'position_x' => 1, 'position_y' => 0],
            ['table_number' => '3', 'capacity' => 4, 'status' => 'available', 'position_x' => 2, 'position_y' => 0],
            ['table_number' => '4', 'capacity' => 4, 'status' => 'available', 'position_x' => 3, 'position_y' => 0],
            ['table_number' => '5', 'capacity' => 6, 'status' => 'available', 'position_x' => 0, 'position_y' => 1],
            ['table_number' => '6', 'capacity' => 6, 'status' => 'available', 'position_x' => 1, 'position_y' => 1],
            ['table_number' => '7', 'capacity' => 4, 'status' => 'available', 'position_x' => 2, 'position_y' => 1],
            ['table_number' => '8', 'capacity' => 4, 'status' => 'available', 'position_x' => 3, 'position_y' => 1],
            ['table_number' => '9', 'capacity' => 2, 'status' => 'available', 'position_x' => 0, 'position_y' => 2],
            ['table_number' => '10', 'capacity' => 2, 'status' => 'available', 'position_x' => 1, 'position_y' => 2],
            ['table_number' => '11', 'capacity' => 8, 'status' => 'available', 'position_x' => 2, 'position_y' => 2],
            ['table_number' => '12', 'capacity' => 8, 'status' => 'available', 'position_x' => 3, 'position_y' => 2],
        ];

        foreach ($tables as $table) {
            Table::create($table);
        }
    }
}
