<?php

namespace DBDiff\Generators;

use DBDiff\Helpers\DiffProxyTrait;
use DBDiff\Logger;
use DBDiff\Params\ParamsFactory;

class PhinxGenerator
    implements
    GeneratorInterface
{

    use DiffProxyTrait;

// constants

    /**
     * @var string
     */
    public const DATE_FORMAT = 'YmdHis';

    /**
     * @var string
     */
    protected const MIGRATION_FILE_NAME_NO_NAME_PATTERN = '/^[0-9]{14}\.php$/';

    /**
     * @var string
     */
    protected const MIGRATION_FILE_NAME_PATTERN = '/^\d+_([a-z][a-z\d]*(?:_[a-z\d]+)*)\.php$/i';

//  public properties

    /** @var \DBDiff\Params\DefaultParams */
    public $params;

// protected properties

    /**
     * @var \DBDiff\Generators\DiffSorter
     */
    protected DiffSorter $diffSorter;


    /**
     * @inheritDoc
     */
    public function __construct(
        $diff,
        $params = null
    ) {

        $this->diff       = $diff;
        $this->params     = $params ?? $params = ParamsFactory::get();
        $this->diffSorter = new DiffSorter();

        $dt = new \DateTime( 'now', new \DateTimeZone( 'UTC' ) );

        $migrationVersion = $dt->format( static::DATE_FORMAT );
        $className        = "V{$migrationVersion}";

        if ( is_null( $output = $params->output ) )
        {
            $params->output = getcwd() . "/$migrationVersion.php";
        }
        elseif ( substr( $output, - 1 ) === '/' || is_dir( rtrim( $output, '/' ) ) )
        {
            $output         = rtrim( $output, '/' );
            $params->output .= "$output/$migrationVersion.php";
        }
        else
        {
            $className = static::mapFileNameToClassName( basename( $output ) );
        }

        if ( $params->defaultTemplateString === null )
        {
            $params->defaultTemplateString = <<<PHP
{{\$php}}
/**
 * @noinspection AutoloadingIssuesInspection
 * @noinspection HttpUrlsUsage
 * @noinspection  SpellCheckingInspection
 */

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class {$className} extends AbstractMigration
{
    public function down(): void
    {
{{\$down}}
    }
    
    public function up(): void
    {
{{\$up}}
    }
}

PHP;
        }

    }


    public function getDown()
    {

        return $this->generate( __FUNCTION__ );
    }


    public function getUp()
    {

        return $this->generate( __FUNCTION__ );
    }


    protected function generate(
        $method
    ) {

        $code = '
        $queryBuilder = $this->getQueryBuilder();
        $output = $this->getOutput();

';
        $type = strtolower( str_replace( 'get', '', $method ) );

        Logger::info( sprintf( "Now generating %s migration", strtoupper( $type ) ) );
        $diffs = $this->diffSorter->sort( $this->diff->getAll(), $type );

        foreach ( $diffs as $diff )
        {
            $generatorClass = str_replace( 'DBDiff\\Diff\\', __NAMESPACE__ . '\\DiffToPhinx\\', get_class( $diff ) );
            $generator      = new $generatorClass( $diff, $this );
            $code           .= $generator->$method() . "\n";
        }

        return $code;
    }


    /**
     * Turn file names like '12345678901234_create_user_table.php' into class
     * names like 'CreateUserTable'.
     *
     * @param  string  $fileName  File Name
     *
     * @return string
     */
    public static function mapFileNameToClassName( string $fileName ): string
    {

        $matches = [];
        if ( preg_match( static::MIGRATION_FILE_NAME_PATTERN, $fileName, $matches ) )
        {
            $fileName = $matches[1];
        }
        elseif ( preg_match( static::MIGRATION_FILE_NAME_NO_NAME_PATTERN, $fileName ) )
        {
            return "V" . substr( $fileName, 0, strlen( $fileName ) - 4 );
        }

        $className = str_replace( '_', '', ucwords( $fileName, '_' ) );

        return $className;
    }

}