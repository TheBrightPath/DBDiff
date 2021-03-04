<?php namespace DBDiff\Params;

use DBDiff\Exceptions\CLIException;
use DBDiff\Params;

class ParamsFactory {
    
    public static function get(...$path) {

        $params = static::parse();

        if (empty($params)) {
            return $params;
        }

        return $params->get(... $path);
    }

    protected static function parse() {

        static $params;

        if ($params !== null) { return $params; };

        $cli = new CLIGetter;
        $paramsCLI = $cli->getParams();

        if (!isset($paramsCLI->debug)) {
            error_reporting(E_ERROR);
        }

        $fs = new FSGetter($paramsCLI);
        $paramsFS = $fs->getParams();

        $params = new Params(array_merge((array) $paramsFS, (array) $paramsCLI));

        if (empty($params->server1)) {
            throw new CLIException("A server is required");
        }
        return $params;
    }
}
