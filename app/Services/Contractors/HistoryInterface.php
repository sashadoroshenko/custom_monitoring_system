<?php

namespace App\Services\Contractors;


interface HistoryInterface
{
    /**
     * @param $id
     * @return mixed
     */
    public function getStockHistories($id);

    /**
     * @param $id
     * @return mixed
     */
    public function getPriceHistories($id);
}