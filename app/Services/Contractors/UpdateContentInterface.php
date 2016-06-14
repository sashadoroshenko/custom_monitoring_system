<?php

namespace App\Services\Contractors;

interface UpdateContentInterface
{
    /**
     * @param null $id
     * @return mixed
     */
    public function getItem($id = null);

    /**
     * @return mixed
     */
    public function updateContent();

    
}