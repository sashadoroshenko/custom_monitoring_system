<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Price extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'prices';

    /**
     * The database primary key value.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'item_id',
        'status',
        'price'
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
