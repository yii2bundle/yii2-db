<?php

namespace yii2bundle\db\console\bin;

use Yii;
use yii\console\ExitCode;
use yii\helpers\Console;
use yii2lab\db\domain\helpers\DbHelper;
use yii2rails\extension\console\handlers\RenderHahdler;
use yii2rails\extension\console\helpers\input\Question;
use yii2rails\extension\console\helpers\Output;
use yii2rails\extension\console\base\Controller;
use yii2rails\domain\data\EntityCollection;
use yii2tool\vendor\domain\entities\TestEntity;
use yii2lab\db\domain\helpers\Fixtures;
use yii2rails\extension\console\helpers\input\Select;
use yii2rails\extension\console\helpers\input\Enter;

class FixtureController extends \yii\base\Component
{
	
	public function init() {
		parent::init();
		Output::line();
	}

    /**
     * Export or import fixtures
     */
    public function actionIndex($option = null)
    {
        /** @var Fixtures $fixtures */
        $fixtures = Yii::createObject(Fixtures::class);
        $fixtures->outputHandler = new RenderHahdler;
        $option = Question::displayWithQuit('Select operation', ['Export', 'Import'], $option);
        if($option == 'e') {
            $allTables = DbHelper::tableNameList();
            if(!empty($allTables)) {
                $answer = Select::display('Select tables for export', $allTables, 1);
                $tables = $fixtures->export($answer);
                Output::items($tables, 'Exported tables');
            } else {
                Output::block("not tables for export!");
            }
        } elseif($option == 'i') {
            $allTables = $fixtures->fixtureNameList();
            if(!empty($allTables)) {
                $answer = Select::display('Select tables for import', $allTables, 1);
                $tables = $fixtures->import($answer);
                Output::items($tables, 'Imported tables');
            } else {
                Output::block("not tables for import!");
            }
        }
    }

    /**
     * Export or import one table
     */
    public function actionOne($option = null)
    {
        /** @var Fixtures $fixtures */
        $fixtures = Yii::createObject(Fixtures::class);
        $fixtures->outputHandler = new OutputHandler;
        $option = Question::displayWithQuit('Select operation', ['Export', 'Import'], $option);
        if($option == 'e') {
            $table = Enter::display('Enter table name for export');
            $tables = $fixtures->export([$table]);
            Output::items($tables, 'Exported tables');
        } elseif($option == 'i') {
            $table = Enter::display('Enter table name for import');
            $tables = $fixtures->import([$table]);
            Output::items($tables, 'Imported tables');
        }
    }

}
