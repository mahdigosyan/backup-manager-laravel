<?php namespace BackupManager\Laravel;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use BackupManager\Databases\DatabaseProvider;
use BackupManager\Procedures\RestoreProcedure;
use BackupManager\Filesystems\FilesystemProvider;
/**
 * Class DbRestoreCommand
 * @package BackupManager\Laravel
 */
class DbRestoreCommand extends Command {
    use AutoComplete;
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'db:restore';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Restore a database backup.';

    /**
     * The required arguments.
     *
     * @var array
     */
    private $required = ['source', 'sourcePath', 'database', 'compression'];

    /**
     * The missing arguments.
     *
     * @var array
     */
    private $missingArguments;
    /**
     * @var \BackupManager\Procedures\RestoreProcedure
     */
    private $restore;

    /**
     * @var \BackupManager\Filesystems\FilesystemProvider
     */

