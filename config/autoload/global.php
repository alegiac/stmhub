<?php
return array (
	'doctrine' => array (
		'configuration' => array(
			'orm_default' => array (
				'proxy_dir' => 'data/DoctrineORMModule/Proxy',
				'proxy_namespace' => 'DoctrineORMModule\Proxy',
			),
		),
		'connection' => array (
			// default connection name
			'orm_default' => array (
				'driverClass' => 'Doctrine\DBAL\Driver\PDOMySql\Driver',
				'params' => array (
					'host'     => '127.0.0.1',
					'port'     => '3306',
					'dbname'   => 'smiletomove_learning',
					'user' => 'root',
					'password' => 'pilotv5',
				)
			)
		),
	),
	'EddieJaoude\Zf2Logger' => array (
	
		// will add the $logger object before the current PHP error handler
		'registerErrorHandler'     => true, // errors logged to your writers
		'registerExceptionHandler' => true, // exceptions logged to your writers
	
		// multiple zend writer output & zend priority filters
		'writers' => array(
			'standard-output-file' => array (
				'adapter' => '\Zend\Log\Writer\Stream',
				'options' => array (
					'output' => 'data/logs/production___'.date('Ymd')."___app.log",
										),
				'filter' => \Zend\Log\Logger::INFO,
				'enabled' => true,
			),
			'standard-error-file' => array (
				'adapter' => '\Zend\Log\Writer\Stream',
				'options' => array (
					'output' => 'data/logs/production___'.date('Ymd')."___err.log",
										),
				'filter' => \Zend\Log\Logger::WARN,
				'enabled' => true,
			),
		)
	),
	'service_manager' => array (
        'aliases' => array  (
			'Logger' => 'EddieJaoude\Zf2Logger'
        ),
	),
	'app_output' => array(
            'email' => array (
                'smtp_password' => 'ghyBtOzWBCfJT1XIQxSnGg',
		'from' => 'info@smiletomove.it',
		'subject' => 'Notifica sessione di esame',
		'bcc' => 'd.oliosi@smiletomove.it',
            )
	)
);
