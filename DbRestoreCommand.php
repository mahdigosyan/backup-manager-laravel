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
    private $filesystems;

    /**
     * @var \BackupManager\Databases\DatabaseProvider
     */
    private $databases;

    /**
     * @param \BackupManager\Procedures\RestoreProcedure $restore
     * @param \BackupManager\Filesystems\FilesystemProvider $filesystems
     * @param \BackupManager\Databases\DatabaseProvider $databases
     */
    public function __construct(RestoreProcedure $restore, FilesystemProvider $filesystems, DatabaseProvider $databases) {
        parent::__construct();
        $this->restore = $restore;
        $this->filesystems = $filesystems;
        $this->databases = $databases;
    }

    /**
     *
     */
    public function fire() {
        if ($this->isMissingArguments()) {
            $this->displayMissingArguments();
            $this->promptForMissingArgumentValues();
            $this->validateArguments();
        }

        $this->info('Downloading and importing backup...');
        $this->restore->run(
            $this->option('source'),
            $this->option('sourcePath'),
            $this->option('database'),
            $this->option('compression')
        );
        


