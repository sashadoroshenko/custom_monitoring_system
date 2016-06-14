<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 13.06.2016
 * Time: 17:36
 */

namespace App\Services\Contractors;


use App\User;

interface CronJobUpdateDataInterface
{

    /**
     * @param User $user
     * @return mixed
     */
    public function updateContent(User $user);


    /**
     * @param User $user
     * @param $response
     * @param $item
     * @return mixed
     */
    public function getStock(User $user, $response, $item);


    /**
     * @param User $user
     * @param $response
     * @param $item
     * @return mixed
     */
    public function getPrice(User $user, $response, $item);


    /**
     * @param User $user
     * @param $response
     * @param $item
     * @param $oldValue
     * @param string $type
     * @param string $search
     * @return mixed
     */
    public function notifications(User $user, $response, $item, $oldValue, $type = 'price', $search = 'salePrice');

}