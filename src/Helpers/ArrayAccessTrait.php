<?php

namespace DBDiff\Helpers;

trait ArrayAccessTrait
{

    /**
     * @inheritDoc
     */
    public function offsetExists( $offset )
    {

        return property_exists( $this, $offset );
    }


    /**
     * @inheritDoc
     */
    public function offsetGet( $offset )
    {

        return $this->$offset;
    }


    /**
     * @inheritDoc
     */
    public function offsetSet(
        $offset,
        $value
    ) {

        $this->$offset = $value;
    }


    /**
     * @inheritDoc
     */
    public function offsetUnset( $offset )
    {

        unset( $this->$offset );
    }

}