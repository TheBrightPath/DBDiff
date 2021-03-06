<?php namespace DBDiff\Generators\DiffToSQL;

use DBDiff\Generators\SQLGenInterface;


class AlterTableAddKeySQL implements SQLGenInterface {

    function __construct($obj) {
        $this->obj = $obj;
    }
    
    public function getUp() {
        $table = $this->obj->table;
        $schema = $this->obj->diff->getNewValue();
        return "ALTER TABLE `$table` ADD $schema;";
    }

    public function getDown() {
        $table = $this->obj->table;
        $key   = $this->obj->key;
        return "ALTER TABLE `$table` DROP INDEX `$key`;";
    }

}
