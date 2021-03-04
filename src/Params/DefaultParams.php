<?php

namespace DBDiff\Params;

class DefaultParams
    extends
    Params
{

//  public properties

    /**
     * @inheritdoc
     */
    public $server1 = [];

    /**
     * @inheritdoc
     */
    public $server2 = [];

    /**
     * @inheritdoc
     */
    public $format = 'sql';

    /**
     * @inheritdoc
     */
    public $template = '';

    /**
     * @inheritdoc
     */
    public $type = 'schema';

    /**
     * @inheritdoc
     */
    public $include = 'up';

    /**
     * @inheritdoc
     */
    public $nocomments = false;

    /**
     * @inheritdoc
     */
    public $output = null;

    /**
     * @inheritdoc
     */
    public $debug = false;

    /**
     * @inheritdoc
     */
    public $input = [];

    /**
     * @inheritdoc
     */
    public $filters;

    /**
     * @inheritdoc
     */
    public $requires = [];

    /**
     * @inheritdoc
     */
    public $defaultTemplateString;

    /**
     * @inheritdoc
     */
    public $fieldsToIgnore;

    /**
     * @inheritdoc
     */
    public $tablesToDiff;

    /**
     * @inheritdoc
     */
    public $tablesToIgnore;

    /**
     * @inheritdoc
     */
    public $constraints;

}
