<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'items';

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
        'user_id',
        'itemID',
//        'userID',
        'price',
        'title',
        'stock',
        'alert_desktop',
        'alert_email',
        'alert_sms',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function prices()
    {
        return $this->hasMany(Price::class);
    }

    public function stocks()
    {
        return $this->hasMany(Stock::class);
    }
}
