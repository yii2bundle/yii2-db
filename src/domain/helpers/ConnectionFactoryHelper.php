<?php

namespace yii2lab\db\domain\helpers;

use yii\db\Connection;

class ConnectionFactoryHelper
{

    /** @var Connection[] */
    private static $connections = [];

    /**
     * @param string $name
     * @return Connection
     *
     * example:
     *
     $connection = ConnectionHelper::getConnection('main');
     $query = 'SELECT * FROM "common"."migration" LIMIT 50';
     $results =  $connection->createCommand($query)->queryAll();
     */
    public static function getConnectionByName(string $name) : Connection {
        if(!isset(self::$connections[$name])) {
            $connectionFromEnv = DbHelper::getConfigFromEnv($name);
            self::$connections[$name] = self::createConnectionFromConfig($connectionFromEnv);
        }
        return self::$connections[$name];
    }

    /**
     * @param array $config
     * @return Connection
     * @throws \yii\db\Exception
     *
    $configDb = [
        'driver' => 'pgsql',
        'username' => 'postgres',
        'password' => 'postgres',
        'dbname' => 'dev_yutpl',
        'defaultSchema' => 'common',
    ];
    $connection = ConnectionFactoryHelper::createConnectionFromConfig($configDb);
    $query = 'SELECT * FROM "common"."migration" LIMIT 50';
    $results =  $connection->createCommand($query)->queryAll();
     */
    public static function createConnectionFromConfig(array $config) : Connection {
        $config = DbHelper::adapterConfig($config);
        $connection = new Connection($config);
        $connection->open();
        return $connection;
    }

}
