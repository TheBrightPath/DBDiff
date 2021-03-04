<?php namespace DBDiff\Diff;


class AlterTableChangeKey extends Step {

    function __construct($table, $key, $diff) {
        $this->table = $table;
        $this->key = $key;
        $this->diff = $diff;
    }
}
