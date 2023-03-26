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
