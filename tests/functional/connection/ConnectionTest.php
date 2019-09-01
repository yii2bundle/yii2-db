<?php

namespace tests\functional\connetcion;

use yii2lab\db\domain\helpers\ConnectionFactoryHelper;
use yii2lab\db\domain\helpers\ConnectionService;
use yii2rails\extension\cache\InvalidArgumentException;
use yii2rails\extension\cache\Cache;
use yii2rails\extension\cache\CacheItem;
use yii2rails\extension\container\Container;
use yii2rails\extension\encrypt\exceptions\SignatureInvalidException;
use yii2rails\extension\encrypt\helpers\JwtService;
use yii2tool\test\Test\Unit;

class ConnectionTest extends Unit {
	
	const PACKAGE = 'yii2bundle/yii2-db';

    public function testRead() {
        $configDb = [
            'driver' => 'sqlite',
            'dbname' => __DIR__ . '\..\..\_data\sqlite\main.db',
        ];
        $connection = ConnectionFactoryHelper::createConnectionFromConfig($configDb);
        $query = 'SELECT * FROM "migration" LIMIT 50';
        $results =  $connection->createCommand($query)->queryAll();
        $this->tester->assertCount(43, $results);
    }

    public function testRead2() {
        $definitions = [
            'db' => [
                'class' => ConnectionService::class,
                'connections' => [
                    'main' => [
                        'driver' => 'sqlite',
                        'dbname' => __DIR__ . '\..\..\_data\sqlite\main.db',
                    ],
                    'slave' => [
                        'driver' => 'sqlite',
                        'dbname' => __DIR__ . '\..\..\_data\sqlite\main.db',
                    ],
                ],
            ],
        ];

        $container = new Container($definitions);
        $mainConnection = $container->db->getConnection('main');
        $slaveConnection = $container->db->getConnection('slave');

        $query = 'SELECT * FROM "migration" LIMIT 50';

        $results =  $slaveConnection->createCommand($query)->queryAll();
        $this->tester->assertCount(43, $results);
    }

}
