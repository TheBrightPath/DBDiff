<?php

namespace DBDiff\Generators;

use DBDiff\Filters\FilterInterface;

interface GeneratorInterface
    extends
    FilterInterface,
    SQLGenInterface
{

}