<?php

namespace DBDiff\Diff;

use DBDiff\Helpers\ArrayAccessTrait;

class DiffData
    implements
    \ArrayAccess
{

    use ArrayAccessTrait;

//  public properties

    /** @var string[] */
    public $keys;

    /** @var \Diff\DiffOp\DiffOpChange[]|\Diff\DiffOp\DiffOpAdd|\Diff\DiffOp\DiffOpRemove */
    public $diff;

    /** @var array */
    public $extra;


    /**
     * DataStep constructor.
     *
     * @param  string                                                                        $table  table name
     * @param  \Diff\DiffOp\DiffOpChange[]|\Diff\DiffOp\DiffOpAdd|\Diff\DiffOp\DiffOpRemove  $diff   diff data
     * @param  array                                                                         $extra  extra fields used
     *                                                                                               for comments or
     *                                                                                               logging
     */
    function __construct(
        array $keys,
        $diff = null,
        $extra = null
    ) {

        $args = func_get_args();
        if ( count( $args ) === 1 )
        {
            if ( ! empty(
            array_diff(
                array_keys( $keys ),
                [
                    'keys',
                    'diff',
                    'extra',
                ]
            )
            ) )
            {
                throw new \InvalidArgumentException();
            }

            $this->keys  = $keys['keys'];
            $this->diff  = $keys['diff'];
            $this->extra = $keys['extra'];
        }
        else
        {
            $this->keys  = $keys;
            $this->diff  = $diff;
            $this->extra = $extra;
        }

        if ( $this->diff === null )
        {
            throw new \InvalidArgumentException();
        }
    }

}