<?php

namespace DBDiff\Params;

use DBDiff\Generators\SQLGenerator;

class Params
{

//  public properties

    /**
     * Specify the source db connection details. If there is only one
     *
     * @var array
     */
    public $server1;

    /**
     * Specify the target db connection details
     *
     * @var array|null
     */
    public $server2;

    /**
     * Specify the target db connection details
     *
     * @var string [sql|phinx]
     */
    public $format;

    /**
     * Specifies the output template, if any. By default will be plain SQL
     *
     * @var string path to the template file
     */
    public $template;

    /**
     * Specifies the type of diff to do either on the schema, data or both
     *
     * @var string Type of output: [schema|data|all]
     */
    public $type;

    /**
     * Specified whether to include the up, down or both data in the output
     *
     * @var string [up|down|all]
     */
    public $include;

    /**
     * By default automated comments starting with the hash (#) character are
     * included in the output file, which can be removed with this parameter
     *
     * @var bool
     */
    public $nocomments = false;

    /**
     * By default, DBDiff will look for a .dbdiff file in the current directory
     * which is valid YAML, which may also be overridden with a config file that
     * lists the database host, user, port and password of the source and target
     * DBs in YAML format (instead of using the command line for it), or any of
     * the other settings e.g. the format, template, type, include, no­comments.
     * Please note: a command­line parameter will always override any config file.
     *
     * @var string
     */
    public $config;

    /**
     * By default will output to the same directory the command is run in if no directory is
     * specified. If a directory is specified, it should exist, otherwise an error will be thrown
     *
     * @var null
     */
    public $output;

    /**
     * Enable or disable warnings
     *
     * @var bool
     */
    public $debug;

    /**
     * The penultimate parameter is what to compare: db1.table1:db2.table3 or​ db1:db2
     * This tool can compare just one table or all tables (entire db) from the database
     *
     * @var array
     */
    public $input;

    /**
     * Filters to be called, one by one, handing it's predecessor as an argument
     *
     * @var \DBDiff\Filters\FilterInterface[];
     */
    public $filters;

    /**
     *These file will be required()
     *
     * @var string[] filenames to be required
     */
    public $requires;

    /**
     * The default template string
     *
     * @see \DBDiff\Templater::getTemplate()
     * @var string|null
     */
    public $defaultTemplateString;

    /**
     * @var array[]|null
     */
    public $fieldsToIgnore;

    /**
     * @var array[]|null
     */
    public $tablesToDiff;

    /**
     * @var string[]|null
     */
    public $tablesToIgnore;

    /**
     * @var array[]|null
     */
    public $constraints;

    /**
     * Memory limit
     *
     * @string
     */
    public $memory;

}
