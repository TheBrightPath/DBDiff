<?php namespace DBDiff\Diff;


class AlterTableDropConstraint extends Step {

    function __construct($table, $name, $diff) {
        $this->table = $table;
        $this->name = $name;
        $this->diff = $diff;
    }
}
