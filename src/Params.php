<?php

namespace DBDiff;

use DBDiff\Generators\SQLGenerator;
use DBDiff\Params\DefaultParams;

class Params
    extends
    DefaultParams
{

    /**
     * Params constructor.
     *
     * @param  \DBDiff\Params\Params  $primary
     * @param  \DBDiff\Params\Params  $default
     */
    public function __construct(
        $primary,
        $default = null
    ) {

        $params = array_unique(
            array_merge(
                array_keys( get_object_vars( $primary ) ),
                array_keys( get_object_vars( $default ?? new \StdClass() ) )
            )
        );

        foreach ( $params as $param )
        {
            $setter = 'set' . ucfirst( $param );
            if ( method_exists( $this, $setter ) )
            {
                // allow a setter to merge from default params
                $this->$setter( $primary->$param ?? $default->$param, $default->$param ?? $this->$param );
            }
            else
            {
                $this->$param = $primary->$param ?? $default->$param ?? $this->$param;
            }
        }

        if ( $this->filters === null )
        {
            switch ( $this->format )
            {
            case 'sql':
                $this->filters[] = SQLGenerator::class;
                break;

            default:
                $class = 'DBDiff\\Generators\\' . ucfirst( $this->format ) . 'Generator';
                if ( class_exists( $class ) )
                {
                    $this->filters[] = $class;
                }

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

        if ( $filters === null )
        {
            return $this;
        }

        $this->filters = (array) $filters;

        return $this;
    }


    /**
     * @param  string  $value
     *
     * @return Params
     */
    public function setMemory(
        ?string $value,
        ?string $default = null
    ): Params {

        if ( $value ?? $default )
        {
            // Increase memory limit
            ini_set( 'memory_limit', $value ?? $default );
        }

        $this->memory = ini_get( 'memory_limit' );

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
