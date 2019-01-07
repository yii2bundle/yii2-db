Руководство
===

Для добавления путей для поиска классов миграций, 
используйте конфиг `env.php`.
В секцию `config.filters` добавьте фильтр:

```php
return [
	//...
	'config' => [
		'filters' => [
			//...
			[
				'class' => 'yii2lab\db\domain\filters\migration\SetPath',
				'path' => [
					'@vendor/yii2bundle/yii2-rbac/src/domain/migrations',
                    '@vendor/yii2bundle/yii2-rest/src/domain/migrations',
				],
				'scan' => [
					'@domain',
				],
			],
			//...
		],
	],
];
```
