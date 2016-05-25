<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('parentItemId');
            $table->string('name');
            $table->float('salePrice');
            $table->integer('upc');
            $table->string('categoryPath');
            $table->text('shortDescription');
            $table->text('longDescription');
            $table->string('brandName');
            $table->string('thumbnailImage');
            $table->string('mediumImage');
            $table->string('largeImage');
            $table->string('productTrackingUrl');
            $table->string('ninetySevenCentShipping');
            $table->string('standardShipRate');
            $table->string('size');
            $table->string('color');
            $table->string('marketplace');
            $table->string('shipToStore');
            $table->string('freeShipToStore');
            $table->string('productUrl');
            $table->string('variants');
            $table->string('categoryNode');
            $table->string('bundle');
            $table->string('clearance');
            $table->string('preOrder');
            $table->string('stock');
            $table->string('attribute');
            $table->string('gender');
            $table->string('addToCartUrl');
            $table->string('affiliateAddToCartUrl');
            $table->string('freeShippingOver50Dollars');
            $table->string('maxItemsInOrder');
            $table->string('giftOptions');
            $table->string('availableOnline');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('items');
    }
}
