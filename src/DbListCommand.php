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
