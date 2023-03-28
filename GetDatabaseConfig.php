<?php namespace BackupManager\Laravel;

use BackupManager\Config\Config;

/**
 * Class GetDatabaseConfig
 *
 * @package BackupManager\Laravel
 */
trait GetDatabaseConfig
{
    /**
     * @param $connections
     * @return Config
     */


     private function getDatabaseConfig($connections) {
        $mapped = array_map(function ($connection) {
            if ( ! in_array($connection['driver'], ['mysql', 'pgsql'])) {
                return;
            }
