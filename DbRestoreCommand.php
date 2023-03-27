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
        $this->line('');
        $root = $this->filesystems->getConfig($this->option('source'), 'root');
        $this->info(sprintf('Successfully restored <comment>%s</comment> from <comment>%s</comment> to database <comment>%s</comment>.',
            $root . $this->option('sourcePath'),
            $this->option('source'),
            $this->option('database')
        ));
    }
    /**
     * @return bool
     */
    private function isMissingArguments() {
        foreach ($this->required as $argument) {
            if ( ! $this->option($argument)) {
                $this->missingArguments[] = $argument;
            }
        }
        return (bool) $this->missingArguments;
    }

    /**
     * @return void
     */
    private function displayMissingArguments() {
        $formatted = implode(', ', $this->missingArguments);
        $this->info("These arguments haven't been filled yet: <comment>{$formatted}</comment>");
        $this->info('The following questions will fill these in for you.');
        $this->line('');
    }

    /**
     * @return void
     */
    private function promptForMissingArgumentValues() {
        foreach ($this->missingArguments as $argument) {
            if ($argument == 'source') {
                $this->askSource();
            } elseif ($argument == 'sourcePath') {
                $this->askSourcePath();
            } elseif ($argument == 'database') {
                $this->askDatabase();
            } elseif ($argument == 'compression') {
                $this->askCompression();
            }
            $this->line('');
        }
    }

    /**
     *
     */
    private function askSource() {
        $providers = $this->filesystems->getAvailableProviders();
        $formatted = implode(', ', $providers);
        $this->info("Available storage services: <comment>{$formatted}</comment>");
        $source = $this->autocomplete("From which storage service do you want to choose?", $providers);
        $this->input->setOption('source', $source);
    }

    /**
     *
     */
    private function askSourcePath() {
        // ask path
        $root = $this->filesystems->getConfig($this->option('source'), 'root');
        $path = $this->ask("From which path do you want to select?<comment> {$root}</comment>");
        $this->line('');

        // ask file
        $filesystem = $this->filesystems->get($this->option('source'));
        $contents = $filesystem->listContents($path);

        $files = [];

        foreach ($contents as $file) {
            if ($file['type'] == 'dir') continue;
            $files[] = $file['basename'];
        }

        if (empty($files)) {
            $this->info('No backups were found at this path.');
            return;
        }

        $rows = [];
        foreach ($contents as $file) {
            if ($file['type'] == 'dir') continue;
            $rows[] = [
                $file['basename'],
                key_exists('extension', $file) ? $file['extension'] : null,
                $this->formatBytes($file['size']),
                date('D j Y  H:i:s', $file['timestamp'])
            ];
        }
        $this->info('Available database dumps:');
        $this->table(['Name', 'Extension', 'Size', 'Created'], $rows);
        $filename = $this->autocomplete("Which database dump do you want to restore?", $files);
        $this->input->setOption('sourcePath', "{$path}/{$filename}");
    }

    /**
     *
     */



