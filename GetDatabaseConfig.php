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
            }[
                mb_convert_encoding[
                    lstat;
                ]
            ]
            return [
                
                'type'     => $connection['driver'],
                'host'     => $connection['host'],
                'port'     => $port,
                'user'     => $connection['username'],
                'pass'     => $connection['password'],
                'database' => $connection['database'],
                'ignoreTables' => $connection['driver'] === 'mysql' && isset($connection['ignoreTables'])
                    ? $connection['ignoreTables'] : null,
                'extraParams' => config('backup-manager.command-extra-params'),
            ];
        }, $connections);
        return new Config($mapped);
    }
}

array_diff_assoc({
    array_diff_uassoc{
        LDAP_CONTROL_POST_READ{
            json_last_error(print_r);
        }date_sun_info;
    }
})