<?php

namespace DBDiff;

use DBDiff\Params\DefaultParams;

class Params
    extends
    DefaultParams
{

    /**
     * Params constructor.
     */
    public function __construct( $params )
    {

        foreach ( $params as $param => &$value )
        {
            $setter = 'set' . ucfirst( $param );
            if ( method_exists( $this, $setter ) )
            {
                $this->$setter( $value );
            }
            else
            {
                $this->$param = $value;
            }
        }
    }


    public function get( ...$path )
    {

        if ( count( $path ) === 0 )
        {
            return $this;
        }

        if ( count( $path ) === 1
            && ( $x = preg_split(
                '@[./\\\\]@',
                $path[0]
            ) )
            && count( $x ) > 1
        )
        {
            $path =& $x;
        }

        // get the root
        $value = $this;

        // walk through the keys
        foreach ( $path as $segment )
        {
            if ( $value === null )
            {
                return null;
            }

            if ( \is_object( $value ) )
            {
                $value = $value->$segment ?? null;
                continue;
            }

            if ( ! \is_array( $value ) )
            {
                return null;
            }

            if ( \array_key_exists( $segment, $value ) )
            {
                $value = $value[ $segment ];
                continue;
            }

            return null;
        }

        return $value;
    }


    /**
     * @param ...$path
     *
     * @return array
     */
    public function getArray( ...$path ): array
    {

        $value = $this->get( ...$path );

        if ( empty( $value ) )
        {
            return [];
        }

        return (array) $value;
    }


    /**
     * @param  array[]|null  $constraints
     *
     * @return Params
     */
    public function setConstraints( $constraints ): Params
    {

        if ( empty( $constraints ) )
        {
            return $this;
        }

        $this->constraints = (array) $constraints;

        array_walk(
            $this->constraints,
            static function ( &$constraints )
            {

                $constraint = array_map(
                    static function ( $constraint )
                    {

                        $a = str_replace( '`x`.', '`a`.', $constraint, $found );
                        if ( ! $found )
                        {
                            return "($constraint)";
                        }
                        $b = str_replace( '`x`.', '`b`.', $constraint, $found );

                        return "($a) AND ($b)";
                    },
                    $constraints
                );

                $constraints = empty( $constraint )
                    ? ''
                    : ' AND ' . implode( ' AND ', $constraint );
            }
        );

        return $this;
    }


    /**
     * @param  \DBDiff\Filters\FilterInterface[]  $filters
     *
     * @return Params
     */
    public function setFilters( $filters ): Params
    {

        $this->filters = (array) $filters;

        return $this;
    }


    /**
     * @param  string[]  $requires
     *
     * @return Params
     */
    public function setRequires( $requires ): Params
    {

        $requires = array_unique( (array) $requires );

        foreach ( $requires as $require )
        {
            ob_start();
            $result = require( $require );

            if ( $result === null )
            {
                $result = ob_get_clean();
            }

            ob_end_clean();

            $this->requires[ $require ] = $result;
        }

        return $this;
    }

}
