<?php

namespace yii2lab\db\domain\helpers;

use yii2rails\app\domain\enums\AppEnum;
use yii2rails\app\domain\helpers\EnvService;
use yii2lab\db\domain\enums\DbDriverEnum;

class DbHelper {
	
	public static function getConfigFromEnv($name) {
		$connectionFromEnv = EnvService::getConnection($name);
		$connectionFromEnv = DbHelper::adapterConfig($connectionFromEnv);
		return $connectionFromEnv;
	}
	
	private static function adapterConfig($connection) {
		$connection = self::forgeMigrator($connection);
		if($connection['driver'] == DbDriverEnum::PGSQL) {
			$connection = PostgresHelper::postgresSchemaMap($connection);
		}
		
		if(empty($connection['dsn'])) {
			$connection['dsn'] = self::getDsn($connection);
		}
		
		$connection = self::clean($connection);
		return $connection;
	}
	
	private static function forgeMigrator($connection) {
		if(empty($connection['migrator'])) {
			return $connection;
		}
		$migrator = $connection['migrator'];
		unset($connection['migrator']);
		if(APP != AppEnum::CONSOLE) {
			return $connection;
		}
		foreach($migrator as $name => $value) {
			$connection[$name] = $value;
		}
		return $connection;
	}
	
	private static function getDsn($connection) {
		if($connection['driver'] == DbDriverEnum::SQLITE) {
			$connection['dsn'] = $connection['driver'] . ':' . $connection['dbname'];
		} else {
			$connection['host'] = isset($connection['host']) ? $connection['host'] : 'localhost';
			
			$dsn = $connection['driver'] . ':';
			$dsnParams = [];
			$dsnParams[] = 'host=' . $connection['host'];
			if($connection['port']) {
				$dsnParams[] = 'port=' . $connection['port'];
			}
			$dsnParams[] = 'dbname=' . $connection['dbname'];
			
			$connection['dsn'] = $connection['driver'] . ':' . implode(';', $dsnParams);
		}
		return $connection['dsn'];
	}
	
	private static function clean($connection) {
		unset($connection['driver']);
		unset($connection['host']);
		unset($connection['port']);
		unset($connection['dbname']);
        unset($connection['map']);
		return $connection;
	}
	
}
