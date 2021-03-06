<?php

namespace App\Services\Contractors;

interface CronJobUpdateDataInterface
{

    /**
     * @return mixed
     */
    public function updateContent();


    /**
     * @param $response
     * @param $item
     * @return mixed
     */
    public function getStock($response, $item);


    /**
     * @param $response
     * @param $item
     * @return mixed
     */
    public function getPrice($response, $item);


    /**
     * @param $response
     * @param $item
     * @param $oldValue
     * @param string $type
     * @param string $search
     * @return mixed
     */
    public function notifications($response, $item, $oldValue, $type = 'price', $search = 'salePrice');

}