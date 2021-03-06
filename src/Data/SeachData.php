<?php


namespace App\Data;


use App\Entity\Campus;

class SeachData
{

    /**
     * @var Campus[]
     */
    public $campus;
    /**
     * @var string
     */
    public $q = '';
    /**
     * @var \DateTime
     */
    public $start_Date;
    /**
     * @var \DateTime
     */
    public $end_Date;
    /**
     * @var boolean
     */
    public $sorties = false;
    /**
     * @var string
     */
    public $sorties2 = false;
    /**
     * @var string
     */
    public $sorties3 = false;
    /**
     * @var boolean
     */
    public $sorties4 = false;
}