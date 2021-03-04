<?php

namespace DBDiff\Diff;

use DBDiff\Filters\FilterInterface;
use DBDiff\Helpers\ArrayAccessTrait;
use DBDiff\Params;

class Collection
    implements
    FilterInterface
{

    use ArrayAccessTrait;

// protected properties

    /** @var object */
    protected $params;

    /** @var \DBDiff\Diff\Step[] */
    protected $data = [];

    /** @var \DBDiff\Diff\Step[] */
    protected $schema = [];


    /**
     * Collection constructor.
     *
     * @param  \DBDiff\Diff\Step[]  $data
     * @param  \DBDiff\Diff\Step[]  $schema
     */
    public function __construct(
        $params,
        $schema = null,
        $data = null
    ) {

        $this->params = $params;
        $this->schema = $schema ?? [];
        $this->data   = $data ?? [];
    }


    /**
     * @return \DBDiff\Diff\Step[]
     */
    public function getAll(): array
    {

        return array_merge( $this->schema, $this->data );
    }


    /**
     * @return \DBDiff\Diff\Step[]
     */
    public function getData(): array
    {

        return $this->data;
    }


    /**
     * @param  \DBDiff\Diff\Step[]  $data
     *
     * @return CollectionInterface
     */
    public function setData( array $data ): CollectionInterface
    {

        $this->data = $data;

        return $this;
    }


    public function getDiff(): CollectionInterface
    {

        return $this;
    }


    /**
     * @return object
     */
    public function getParams(): Params
    {

        return $this->params;
    }


    /**
     * @return \DBDiff\Diff\Step[]
     */
    public function getSchema(): array
    {

        return $this->schema;
    }


    /**
     * @param  \DBDiff\Diff\Step[]  $schema
     *
     * @return CollectionInterface
     */
    public function setSchema( array $schema ): CollectionInterface
    {

        $this->schema = $schema;

        return $this;
    }


    /**
     * @inheritDoc
     */
    public function offsetUnset( $offset )
    {

        if ( in_array(
            $offset,
            [
                'data',
                'schema',
            ]
        ) )
        {
            $this->$offset = [];
        }
        else
        {
            unset( $this->$offset );
        }
    }

}