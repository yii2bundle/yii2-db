<?php

namespace yii2lab\db\domain\drivers;

use Yii;
use yii2lab\db\domain\helpers\TableHelper;
use yii2lab\db\domain\interfaces\DbDriverInterface;
use yii2rails\extension\common\helpers\Helper;
use yii2lab\db\domain\interfaces\DriverInterface;

class DbDriver implements DriverInterface
{
	const DRIVER_NAMESPACE = 'yii2lab\db\domain\drivers\db';
	/**
	 * @var DbDriverInterface
	 */
	private $driver;
	
	public function __construct()
	{
		$db = Helper::getCurrentDbDriver();
		$this->driver = Yii::createObject(self::DRIVER_NAMESPACE . '\\' . ucfirst($db) . 'Driver');
	}
	
	public function disableForeignKeyChecks($table) {
        $table = TableHelper::getGlobalName($table);
		return $this->driver->disableForeignKeyChecks($table);
	}
	
	public function truncateData($table) {
        $table = TableHelper::getGlobalName($table);
		return $this->driver->clearTable($table);
	}
	
	public function beginTransaction()
	{
		return $this->driver->beginTransaction();
	}
	
	public function commitTransaction()
	{
		return $this->driver->commitTransaction();
	}
	
	public function loadData($table)
	{
        $table = TableHelper::getGlobalName($table);
		return $this->driver->loadData($table);
	}

	public function saveData($table, $data)
	{
        $table = TableHelper::getGlobalName($table);
		return $this->driver->saveData($table, $data);
	}
	
	public function getNameList()
	{
		return $this->driver->getNameList();
	}
	
}
