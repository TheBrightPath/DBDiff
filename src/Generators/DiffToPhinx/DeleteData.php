<?php

namespace DBDiff\Generators\DiffToPhinx;

use DBDiff\Generators\SQLGenInterface;

class DeleteData
    extends
    Data
    implements
    SQLGenInterface
{

    public function getDown()
    {
        $this->method = __FUNCTION__;

        return $this->getInsert( $this->obj->table, $this->obj->diff['diff']->getOldValue() );
    }


    public function getUp()
    {
        $this->method = __FUNCTION__;

        return $this->getDelete( $this->obj->table, $this->obj->diff['keys'] );
    }

}
