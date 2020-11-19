<?php namespace DBDiff\DB;

use Illuminate\Database\Capsule\Manager as Capsule;
use DBDiff\Exceptions\DBException;
use Illuminate\Database\Events\StatementPrepared;
use Illuminate\Events\Dispatcher;
use Illuminate\Support\Arr;


class DBManager {

    /**
     *  @var \DBDiff\Params
     */
    public $params;

    /**
     * @var \Illuminate\Database\Capsule\Manager
     */
    private Capsule $capsule;


    function __construct($params = null) {
        $this->params = $params;
        $this->capsule = new Capsule;
        $dispatcher = new Dispatcher();
        $dispatcher->listen(StatementPrepared::class, function ($event) {
            $event->statement->setFetchMode(\PDO::FETCH_ASSOC);
        });

        $this->capsule->setEventDispatcher($dispatcher);
    }

    public function connect($params = null) {
        $this->params = $params = $params ?? $this->params;
        foreach ($params->input as $key => $input) {
            if ($key === 'kind') continue;
            $server = $params->{$input['server']};
            $db = $input['db'];
            $this->capsule->addConnection([
                'driver'    => 'mysql',
                'host'      => $server['host'],
                'port'      => $server['port'],
                'database'  => $db,
                'username'  => $server['user'],
                'password'  => $server['password'],
                'charset'   => 'utf8',
                'collation' => 'utf8_unicode_ci'
            ], $key);
        }
    }

    public function testResources() {
        $params = $this->params;
        $this->testResource($params->input['source'], 'source');
        $this->testResource($params->input['target'], 'target');
    }

    public function testResource($input, $res) {
        try {
            $this->capsule->getConnection($res);
        } catch(\Exception $e) {
            throw new DBException("Can't connect to target database");
        }
        if (!empty($input['table'])) {
            try {
                $this->capsule->getConnection($res)->table($input['table'])->first();
            } catch(\Exception $e) {
                throw new DBException("Can't access target table");
            }
        }
    }

    public function getDB($res) {
        return $this->capsule->getConnection($res);
    }

    public function getTables($connection) {
        $params = $this->params;
        $result = $this->getDB($connection)->select("show tables");
        $result = Arr::flatten($result);
        if (isset($params->tablesToDiff)) {
            $result = array_intersect($result, $params->tablesToDiff);
        }
        if (isset($params->tablesToIgnore)) {
            $result = array_diff($result, $params->tablesToIgnore);
        }
        return $result;
    }

    public function getColumns($connection, $table) {
        $result = $this->getDB($connection)->select("show columns from `$table`");
        return Arr::pluck($result, 'Field');
    }

    public function getKey($connection, $table) {
        $ukey = $this->params->get('compareBy', $table);
        if ($ukey !== null) {
            return $ukey;
        }
        $keys = $this->getDB($connection)->select("show indexes from `$table`");
        $ukey = [];
        foreach ($keys as $key) {
            if ($key['Key_name'] === 'PRIMARY') {
                $ukey[] = $key['Column_name'];
            }
        }
        return $ukey;
    }

}
