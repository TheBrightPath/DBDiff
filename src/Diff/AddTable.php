<?php namespace DBDiff\Diff;


class AddTable extends Step {

    function __construct($table, $connection) {
        $this->table = $table;
        $this->connection = $connection;
    }
}
