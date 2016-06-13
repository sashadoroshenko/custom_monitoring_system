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
        'url',
        'title',
        'stock',
        'alert_desktop',
        'alert_email',
        'alert_sms',
    ];

    /**
     * Set the url's.
     *
     * @param  string  $value
     * @return string
     */
    public function setUrlAttribute($value)
    {
        if(isset(explode('=http://', urldecode($value))[1])){
            $this->attributes['url'] = "http://" . explode('=http://', urldecode($value))[1];
        }
    }

    /**
     * Get the user that owns the item.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the prices for the item.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function prices()
    {
        return $this->hasMany(Price::class);
    }

    /**
     * Get the stocks for the item.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function stocks()
    {
        return $this->hasMany(Stock::class);
    }
}
