<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = ['name', 'key'];

    public static function boot()
    {
        parent::boot();

        self::saved(function($item){
            $item->history()->create([
                'name' => $item->name,
                'key' => $item->key,
            ]);
        });

        self::deleting(function($item){
            $item->history()->delete();
        });
    }

    /**
     * Item updates history
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function history()
    {
        return $this->hasMany(ItemHistory::class);
    }
}
