<?php

namespace DBDiff\Generators\DiffToPhinx;

use DBDiff\Generators\SQLGenInterface;

class UpdateData
    extends
    Data
    implements
    SQLGenInterface
{

    public function getDown()
    {

        return $this->getUpdate( __FUNCTION__ );
    }


    public function getUp()
    {

        return $this->getUpdate( __FUNCTION__ );
    }

}
