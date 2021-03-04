<?php

namespace DBDiff\Helpers;

use DBDiff\Diff\CollectionInterface;
use DBDiff\Params;

trait DiffProxyTrait
{

// protected properties

    /**
     * @var \DBDiff\Diff\CollectionInterface
     */
    protected $diff;


    public function getAll(): array
    {

        return $this->getDiff()->getAll();
    }


    public function getData(): array
    {

        return $this->getDiff()->getData();
    }


    public function getDiff(): CollectionInterface
    {

        return $this->diff;
    }


    public function getParams(): Params
    {

        return $this->getDiff()->getParams();
    }


    public function getSchema(): array
    {

        return $this->getDiff()->getSchema();
    }


    public function setData( array $data ): CollectionInterface
    {

        return $this->getDiff()->setData( $data );
    }


    public function setSchema( array $schema ): CollectionInterface
    {

        return $this->getDiff()->setSchema( $schema );
    }


    /**
     * @inheritDoc
     */
    public function offsetExists( $offset )
    {

        return $this->getDiff()->offsetExists( $offset );
    }


    /**
     * @inheritDoc
     */
    public function offsetGet( $offset )
    {

        return $this->getDiff()->offsetGet( $offset );
    }


    /**
     * @inheritDoc
     */
    public function offsetSet(
        $offset,
        $value
    ) {

        $this->getDiff()->offsetSet( $offset, $value );
    }


    /**
     * @inheritDoc
     */
    public function offsetUnset( $offset )
    {

        $this->getDiff()->offsetUnset( $offset );
    }

}