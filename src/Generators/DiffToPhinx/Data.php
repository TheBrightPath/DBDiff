<?php

namespace DBDiff\Generators\DiffToPhinx;

class Data
{

// protected properties

    /** @var \DBDiff\Generators\PhinxGenerator */
    protected $generator;

    /** @var \DBDiff\Diff\DataStep */
    protected $obj;


    /**
     * Data constructor.
     *
     * @param  \DBDiff\Diff\DataStep              $obj  diff data object
     * @param  \DBDiff\Generators\PhinxGenerator  $generator
     */
    function __construct(
        $obj,
        $generator
    ) {

        $this->obj       = $obj;
        $this->generator = $generator;
    }


    protected function getComments(
        string $prefix
    ) {

        $code = '';
        if ( $comments = $this->generator->params->get( 'comments', $this->obj->table ) )
        {
            if ( is_numeric( key( $comments ) ) )
            {
                $comments = array_flip( $comments );
            }

            $commentValues = array_intersect_key( $this->obj->diff->extra, $comments );

            $code .= sprintf(
                '
        $output->writeln( "  - %s %s?%s" );
',
                $prefix,
                $this->obj->table,
                http_build_query( $commentValues, '_' )
            );
        }

        return $code;
    }


    public function getDelete(
        string $table,
        array $keys
    ) {

        $table     = var_export( $table, true );
        $condition = var_export( $keys, true );
        $code      = $this->getComments( 'Deleting' );

        $code .= sprintf(
            '
        $queryBuilder->delete( %s )
                     ->where( %s )
        ;
',
            $table,
            $condition
        );

        return $code;
    }


    protected function getInsert(
        string $table,
        array $values
    ) {

        $table  = var_export( $table, true );
        $fields = var_export( array_keys( $values ), true );
        $data   = var_export( $values, true );
        $code   = $this->getComments( 'Inserting' );

        $code .= sprintf(
            '
        $queryBuilder->insert( %s )
                     ->into( %s )
                     ->values( %s )
                     ->execute()
        ;
',
            $fields,
            $table,
            $data
        );

        return $code;
    }


    protected function getUpdate(
        string $method
    ) {

        $getter = $method === 'getUp'
            ? 'getNewValue'
            : 'getOldValue';

        $values    = $this->getValuesFromArray( $this->obj->diff['diff'], $getter );
        $table     = var_export( $this->obj->table, true );
        $condition = var_export( $this->obj->diff['keys'], true );
        $data      = var_export( $values, true );
        $code      = $this->getComments( 'Updating' );

        $code .= sprintf(
            '
        $queryBuilder->update( %s )
                     ->set( %s )
                     ->where( %s )
                     ->execute()
        ;
',
            $table,
            $data,
            $condition
        );

        return $code;
    }


    protected function &getValuesFromArray(
        array $values,
        string $getter = 'getOldValue'
    ) {

        array_walk(
            $values,
            function (
                &$diff
            ) use
            (
                $getter
            )
            {

                $diff = $diff->$getter();
            }
        );

        return $values;
    }


    protected function arrayToColumns( array $values )
    {

        array_walk(
            $values,
            function (
                &$value,
                $column
            ) {

                $value = var_export( $value, true );
//                $value = $value === null
//                    ? 'null'
//                    : sprintf( "'%s'", addslashes( $value ) );

                $value = sprintf( "'%s' => %s", addslashes( $column ), $value );
            }
        );

        return implode( ', ', $values );

    }


    protected function arrayToKeys(
        array $values,
        bool $useKeys = false
    ) {

        if ( $useKeys )
        {
            $values = array_keys( $values );
        }

        array_walk(
            $values,
            function (
                &$value
            ) {

                $value = sprintf( "'%s'", addslashes( $value ) );
            }
        );

        return implode( ', ', $values );

    }

}
