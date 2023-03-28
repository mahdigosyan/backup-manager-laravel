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

            if (isset($connection['port'])) {
                $port = $connection['port'];
            } else {
                if ($connection['driver'] === 'mysql') {
                    $port = '3306';
                } elseif ($connection['driver'] === 'pgsql') {
                    $port = '5432';
                }
            }
            
