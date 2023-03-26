<?php namespace BackupManager\Laravel;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use BackupManager\Filesystems\FilesystemProvider;
/**
 * Class DbListCommand
 * @package BackupManager\Laravel
 */
class DbListCommand extends Command {
    use AutoComplete;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'db:list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List contents of a backup storage destination.';
    /**
     * @var \BackupManager\Filesystems\FilesystemProvider
     */
    private $filesystems;

    /**
     * The required arguments.
     *
     * @var array
     */
    private $required = ['source', 'path'];

    /**
     * The missing arguments.
     *
     * @var array
     */
    private $missingArguments;


    /**
     * @param FilesystemProvider $filesystems
     */
    public function __construct(FilesystemProvider $filesystems) {
        parent::__construct();
        $this->filesystems = $filesystems;
    }

    /**
     * Execute the console command.
     *
     * @throws \LogicException
     * @throws \BackupManager\Filesystems\FilesystemTypeNotSupported
     * @throws \BackupManager\Config\ConfigFieldNotFound
     * @throws \BackupManager\Config\ConfigNotFoundForConnection
     * @return void
     */
    public function fire() {
        if ($this->isMissingArguments()) {
            $this->displayMissingArguments();
            $this->promptForMissingArgumentValues();
            $this->validateArguments();
        }

        $filesystem = $this->filesystems->get($this->option('source'));
        $contents = $filesystem->listContents($this->option('path'));
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
        $this->table(['Name', 'Extension', 'Size', 'Created'], $rows);
    }
    
