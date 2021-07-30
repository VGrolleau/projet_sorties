<?php


namespace App\Entity;


use Doctrine\Common\Collections\ArrayCollection;


class CollectionCity
{
    protected $cities;

    public function __construct()
    {
        $this->cities = new ArrayCollection();
    }

    public function getCities(): ArrayCollection
    {
        return $this->cities;
    }
}