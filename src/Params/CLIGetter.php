<?php namespace DBDiff\Params;

use DBDiff\Exceptions\CLIException;
use Aura\Cli\CliFactory;


class CLIGetter implements ParamsGetter {
    
    public function getParams(): Params {
        $params = new Params;

        $cliFactory = new CliFactory;
        $context = $cliFactory->newContext($GLOBALS);

        $getopt = $context->getopt([
            /**  @see \DBDiff\Params\Params::$server1 */
            'server1::',

            /**  @see \DBDiff\Params\Params::$server2 */
            'server2::',

            /**  @see \DBDiff\Params\Params::$format */
            'format:',

            /**  @see \DBDiff\Params\Params::$template */
            'template:',

            /**  @see \DBDiff\Params\Params::$type */
            'type:',

            /**  @see \DBDiff\Params\Params::$include */
            'include:',

            /**  @see \DBDiff\Params\Params::$nocomments */
            'nocomments::',

            /**  @see \DBDiff\Params\Params::$config */
            'config:',

            /**  @see \DBDiff\Params\Params::$output */
            'output:',

            /**  @see \DBDiff\Params\Params::$debug */
            'debug::',

            /**  @see \DBDiff\Params\Params::$filters */
            'filters*:',

            /**  @see \DBDiff\Params\Params::$requires */
            'requires*:',

            /**  @see \DBDiff\Params\Params::$tablesToDiff */
            'table*:',

            /**  @see \DBDiff\Params\Params::$tablesToIgnore */
            'ignore*:',
        ]);

        $input = $getopt->get(1);
        if ($input) {
            $params->input = $this->parseInput($input);
        } else throw new CLIException("Missing input");

        if ($getopt->get('--server1'))
            $params->server1 = $this->parseServer($getopt->get('--server1'));
        if ($getopt->get('--server2'))
            $params->server2 = $this->parseServer($getopt->get('--server2'));

        $params->format         = $getopt->get( '--format' );
        $params->template       = $getopt->get( '--template' );
        $params->type           = $getopt->get( '--type' );
        $params->include        = $getopt->get( '--include' );
        $params->nocomments     = $getopt->get( '--nocomments' );
        $params->config         = $getopt->get( '--config' );
        $params->output         = $getopt->get( '--output' );
        $params->debug          = $getopt->get( '--debug' );
        $params->filters        = $getopt->get( '--filters' );
        $params->requires       = $getopt->get( '--requires' );
        $params->tablesToDiff   = $getopt->get( '--table' );
        $params->tablesToIgnore = $getopt->get( '--ignore' );

        return $params;
    }

    protected function parseServer($server) {
        $parts = explode('@', $server);
        $creds = explode(':', $parts[0]);
        $dns   = explode(':', $parts[1]);
        return [
            'user'     => $creds[0],
            'password' => $creds[1],
            'host'     => $dns[0],
            'port'     => $dns[1]
        ];
    }

    protected function parseInput($input) {
        $parts  = explode(':', $input);
        if (sizeof($parts) !== 2) {
            throw new CLIException("You need two resources to compare");
        }
        $first  = explode('.', $parts[0]);
        $second = explode('.', $parts[1]);
        if (sizeof($first) !== sizeof($second)) {
            throw new CLIException("The two resources must be of the same kind");
        }

        if (sizeof($first) === 2) {
            return [
                'kind' => 'db',
                'source' => ['server' => $first[0], 'db' => $first[1]],
                'target' => ['server' => $second[0], 'db' => $second[1]],
            ];
        } else if (sizeof($first) === 3) {
            return [
                'kind' => 'table',
                'source' => ['server' => $first[0],  'db' => $first[1],  'table' => $first[2]],
                'target' => ['server' => $second[0], 'db' => $second[1], 'table' => $second[2]],
            ];
        } else throw new CLIException("Unkown kind of resources");
    }
}
