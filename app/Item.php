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
        'parentItemId', 
        'name', 
        'salePrice', 
        'upc', 
        'categoryPath', 
        'shortDescription', 
        'longDescription', 
        'brandName', 
        'thumbnailImage', 
        'mediumImage', 
        'largeImage', 
        'productTrackingUrl', 
        'ninetySevenCentShipping', 
        'standardShipRate', 
        'size', 
        'color', 
        'marketplace', 
        'shipToStore', 
        'freeShipToStore', 
        'productUrl', 
        'variants', 
        'categoryNode', 
        'bundle', 
        'clearance', 
        'preOrder', 
        'stock', 
        'attribute',
        'gender', 
        'addToCartUrl', 
        'affiliateAddToCartUrl', 
        'freeShippingOver50Dollars', 
        'maxItemsInOrder', 
        'giftOptions', 
        'availableOnline'
    ];
}
