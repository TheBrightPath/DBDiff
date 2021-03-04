<?php namespace DBDiff\DB;

use DBDiff\DB\Schema\DBSchema;
use DBDiff\DB\Schema\TableSchema;
use DBDiff\DB\Data\DBData;
use DBDiff\DB\Data\TableData;
use DBDiff\Diff\Collection;
use DBDiff\Diff\CollectionInterface;
use DBDiff\Filters\FilterInterface;
use DBDiff\Helpers\DiffProxyTrait;
use DBDiff\Params;

class DiffCalculator implements FilterInterface {

    use DiffProxyTrait;

    /**
     * @var \DBDiff\DB\DBManager
     */
    protected DBManager $manager;

    function __construct( $diff, $params = null) {
        $this->manager = new DBManager($params);
    }

    public function getDiff(): CollectionInterface {

        if ($this->diff !== null) {
            return $this->diff;
        }
        
        // Connect and test accessibility
        $this->manager->connect();
        $this->manager->testResources();

        $params = $this->manager->params;

        // Schema diff
        $schemaDiff = [];
        if ($params->type !== 'data') {
            if ($params->input['kind'] === 'db') {
                $dbSchema = new DBSchema($this->manager);
                $schemaDiff = $dbSchema->getDiff();
            } else {
                $tableSchema = new TableSchema($this->manager);
                $schemaDiff = $tableSchema->getDiff($params->input['source']['table']);
            }
        }

        // Data diff
        $dataDiff = [];
        if ($params->type !== 'schema') {
            if ($params->input['kind'] === 'db') {
                $dbData = new DBData($this->manager);
                $dataDiff = $dbData->getDiff();
            } else {
                $tableData = new TableData($this->manager);
                $dataDiff = $tableData->getDiff($params->input['source']['table']);
            }
        }

        return $this->diff = new Collection($params, $schemaDiff, $dataDiff);

    }

    public function getParams(): Params
    {
       return $this->manager->params;
    }

}
