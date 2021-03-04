<?php

namespace DBDiff\Diff;

class DataStep
    extends
    Step
{

//  public properties

    /** @var string */
    public $table;

    /** @var \DBDiff\Diff\DiffData */
    public $diff;


    /**
     * DataStep constructor.
     *
     * @param  string                       $table  table name
     * @param  \DBDiff\Diff\DiffData|array  $diff   diff data
     */
    function __construct(
        $table,
        $diff
    ) {

        $this->table = $table;

        if ( $diff instanceof DiffData )
        {
            $this->diff = $diff;
        }
        else
        {
            $this->diff = new DiffData( $diff );

        }
    }

}