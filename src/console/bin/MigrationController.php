<?php

namespace yii2bundle\db\console\bin;

use Yii;
use yii\console\ExitCode;
use yii\helpers\Console;
use yii2rails\extension\console\helpers\input\Question;
use yii2rails\extension\console\helpers\Output;
use yii2rails\extension\console\base\Controller;
use yii2rails\domain\data\EntityCollection;
use yii2module\vendor\domain\entities\TestEntity;
use yii2rails\extension\console\helpers\input\Enter;
use yii2lab\db\domain\helpers\MigrationHelper;

class MigrationController extends \yii\base\Component
{
	
	public function init() {
		parent::init();
		Output::line();
	}

    /**
     * Generate migration with columns and foreign keys
     */
    public function actionGenerate()
    {
        $tableName = Enter::display('Enter table name');
        $className = MigrationHelper::generateByTableName($tableName);
        Output::block($className, 'Migration created!');
    }

}
