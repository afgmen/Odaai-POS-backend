<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Table extends Model
{
    protected $fillable = [
        'table_number',
        'capacity',
        'status',
        'position_x',
        'position_y',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function currentOrder()
    {
        return $this->hasOne(Order::class)
            ->whereIn('status', ['pending', 'sent_to_kitchen', 'preparing', 'ready']);
    }
}
