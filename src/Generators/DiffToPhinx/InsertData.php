<?php

namespace DBDiff\Generators\DiffToPhinx;

use DBDiff\Generators\SQLGenInterface;

class InsertData
    extends
    Data
    implements
    SQLGenInterface
{

    public function getDown()
    {
        $this->method = __FUNCTION__;

        return $this->getDelete( $this->obj->table, $this->obj->diff['keys'] );
    }


    public function getUp()
    {
        $this->method = __FUNCTION__;

        return $this->getInsert( $this->obj->table, $this->obj->diff['diff']->getNewValue() );
    }

}
