<?php namespace DBDiff\Diff;


class DropTable extends Step {

    function __construct($table, $connection) {
        $this->table = $table;
        $this->connection = $connection;
    }
}
