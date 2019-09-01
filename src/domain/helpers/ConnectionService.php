<?php

namespace yii2lab\db\domain\helpers;

use yii\db\Connection;

class ConnectionService
{

    public $connections = [];
    private $connectionsInstance = [];

    /*public function __get($id) {
        return $this->getConnection($id);
    }*/

    public function __construct(array $connections = [])
    {
        $this->connections = $connections;
    }

    public function getConnection(string $name) : Connection {
        if(!isset($this->connectionsInstance[$name])) {
            $config = $this->getConfig($name);
            $this->connectionsInstance[$name] = ConnectionFactoryHelper::createConnectionFromConfig($config);
        }
        return $this->connectionsInstance[$name];
    }

    private function getConfig(string $name) {
        if(!isset($this->connections[$name])) {
            throw new \InvalidArgumentException('Connection not found');
        }
        $config = $this->connections[$name];
        return $config;
    }

}
