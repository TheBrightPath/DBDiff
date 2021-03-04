<?php

namespace DBDiff\Diff;

use DBDiff\Params;

interface CollectionInterface
    extends
    Params\ParamsGetter,
    \ArrayAccess
{

    /**
     * @return \DBDiff\Diff\Step[]
     */
    public function getAll(): array;


    /**
     * @return \DBDiff\Diff\Step[]
     */
    public function getData(): array;


    /**
     * @return \DBDiff\Diff\Step[]
     */
    public function getSchema(): array;


    /**
     * @param  \DBDiff\Diff\Step[]  $data
     *
     * @return CollectionInterface
     */
    public function setData( array $data ): CollectionInterface;


    /**
     * @param  \DBDiff\Diff\Step[]  $schema
     *
     * @return CollectionInterface
     */
    public function setSchema( array $schema ): CollectionInterface;

}