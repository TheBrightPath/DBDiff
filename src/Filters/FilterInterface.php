<?php

namespace DBDiff\Filters;

use DBDiff\Diff\CollectionInterface;
use DBDiff\Params;

interface FilterInterface
    extends
    CollectionInterface
{

    /**
     * FilterInterface constructor.
     *
     * @param  CollectionInterface                $diff
     * @param  \DBDiff\Params\DefaultParams|null  $params
     */
    public function __construct(
        $diff,
        $params = null
    );


    public function getDiff(): CollectionInterface;


    public function getParams(): Params;

}