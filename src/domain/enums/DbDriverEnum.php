<?php

namespace yii2lab\db\domain\enums;

use yii2rails\extension\enum\base\BaseEnum;

class DbDriverEnum extends BaseEnum {
	
	const MYSQL = 'mysql';
	const PGSQL = 'pgsql';
	const SQLITE = 'sqlite';

}
