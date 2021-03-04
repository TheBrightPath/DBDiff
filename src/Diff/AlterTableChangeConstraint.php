<?php namespace DBDiff\Diff;


class AlterTableChangeConstraint extends Step {

    function __construct($table, $name, $diff) {
        $this->table = $table;
        $this->name = $name;
        $this->diff = $diff;
    }
}
