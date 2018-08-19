<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemHistory extends Model
{
    protected $table = 'items_history';
    protected $fillable = ['item_id', 'name', 'key'];
}
