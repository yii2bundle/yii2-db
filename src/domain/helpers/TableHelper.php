<?php

namespace yii2lab\db\domain\helpers;

use yii\helpers\ArrayHelper;
use yii2lab\app\domain\helpers\EnvService;
use yii2lab\extension\common\helpers\Helper;
use yii2lab\extension\common\helpers\UrlHelper;
use yii2lab\db\domain\enums\DbDriverEnum;
use Yii;
use yii\db\Exception;
use yii\db\Connection;
use yii2lab\db\domain\helpers\DbHelper;
use yii2lab\domain\exceptions\UnprocessableEntityHttpException;
use yii2lab\domain\helpers\ErrorCollection;

class TableHelper
{

    private static $map = null;

	public static function getGlobalName(string $tableName) {
		self::loadMap();
        $schema = ArrayHelper::getValue(self::$map, $tableName);
        if($schema) {
            $tableName = $schema . DOT . $tableName;
        }
		return $tableName;
	}

	private static function loadMap() {
	    if(self::$map === null) {
            $config = EnvService::getConnection('main');
            self::$map = ArrayHelper::getValue($config, 'map', []);
        }
    }
}
