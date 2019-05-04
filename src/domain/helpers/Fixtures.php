<?php

namespace yii2lab\db\domain\helpers;

use Yii;
use yii\base\Component;
use yii2bundle\db\console\bin\OutputHandler;
use yii2lab\db\domain\enums\DbDriverEnum;
use yii2lab\db\domain\interfaces\DriverInterface;
use yii2mod\helpers\ArrayHelper;

class Fixtures extends Component
{

	const DRIVER_NAMESPACE = 'yii2lab\db\domain\drivers';
	private $dbDriver;
	private $diskDriver;
    /**
     * @var OutputHandler
     */
	public $outputHandler;
	
	public function init()
	{
		$this->dbDriver = Yii::createObject(self::DRIVER_NAMESPACE . '\\DbDriver');
		$this->diskDriver = Yii::createObject(self::DRIVER_NAMESPACE . '\\DiskDriver');
	}
	
	public function export($all)
	{
		return $this->copyAll($all, $this->dbDriver, $this->diskDriver);
	}
	
	public function import($all)
	{
		return $this->copyAll($all, $this->diskDriver, $this->dbDriver);
	}
	
	public function tableNameList()
	{
        $driver = ConnectionHelper::getDriverFromDb(Yii::$app->db);
        if($driver == DbDriverEnum::PGSQL) {
            $schemaNames = Yii::$app->db->schema->getSchemaNames();
        } else {
            $schemaNames = [''];
        }
        $tables = [];
        foreach ($schemaNames as $schemaName) {
            $tableColumns = Yii::$app->db->schema->getTableSchemas($schemaName);
            $tableNames = ArrayHelper::getColumn($tableColumns, 'name');
            foreach ($tableNames as $tableName) {
                $table = $schemaName . DOT . $tableName;
                $tables[] = trim($table, DOT);
            }
        }
        return $tables;
	}
	
	public function fixtureNameList()
	{
		$list = $this->diskDriver->getNameList();
		sort($list);
		reset($list);
		return $list;
	}
	
	private function copyAll($all, DriverInterface $fromDriver, DriverInterface $toDriver)
	{
		$result = [];
		if(empty($all)) {
			return $result;
		}
		
		foreach($all as $table) {
		    $this->outputHandler->line('disable foreign key checks for "' . $table . '" table');
			$toDriver->disableForeignKeyChecks($table);
		}
		
		foreach($all as $table) {
            $this->outputHandler->line('truncate data for "' . $table . '" table');
			$toDriver->truncateData($table);
		}
		
		$toDriver->beginTransaction();
		foreach($all as $table) {
            $this->outputHandler->line('copy data for "' . $table . '" table');
			$copyResult = $this->copyData($table, $fromDriver, $toDriver);
			$result[] = $table . ' ' . ($copyResult ? '' : '[fail]') . '';
		}

        $this->outputHandler->line('commit transaction for all');
		$toDriver->commitTransaction();
		return $result;
	}
	
	private function copyData($table, DriverInterface $fromDriver, DriverInterface $toDriver)
	{
		/** @var DriverInterface $fromDriver */
		$data = $fromDriver->loadData($table);
		if(empty($data)) {
			return false;
		}
		
		/** @var DriverInterface $toDriver */
		return $toDriver->saveData($table, $data);
	}
	
}
