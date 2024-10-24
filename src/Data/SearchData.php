<?php

namespace App\Data;

use DateTime;
use App\Entity\Model;
use App\Entity\Category;

class SearchData
{
    /**
     * @var integer
     */
    public $page = 1;

    /**
     * @var string
     */
    public $q = '';

    /**
     * @var Category[]
     */
    public $categories = [];

    /**
     * @var Model[]
     */
    public $model = [];

    /**
     * @var null|integer
     */
    public $minPrice;

    /**
     * @var null|integer
     */
    public $maxPrice;

        /**
     * @var null|integer
     */
    public $minKms;

    /**
     * @var null|integer
     */
    public $maxKms;
    
    /**
     * @var DateTimeImmutable
     */
    public $minCirculationAt;

    /**
     * @var DateTimeImmutable
     */
    public $maxCirculationAt;
}