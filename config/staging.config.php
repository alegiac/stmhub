<?php
/**
 * Configuration file generated by ZF Apigility Admin
 *
 * The previous config file has been stored in application.config.old
 */
return array(
    'modules' => array(
        'DoctrineModule',
        'DoctrineORMModule',
        'ZF\\Apigility',
        'ZF\\Apigility\\Provider',
        'ZF\\ApiProblem',
        'ZF\\MvcAuth',
        'ZF\\OAuth2',
        'ZF\\Hal',
        'ZF\\ContentNegotiation',
        'ZF\\ContentValidation',
        'ZF\\Rest',
        'ZF\\Rpc',
        'ZF\\Versioning',
        'ZF\\DevelopmentMode',
        'ZendDeveloperTools',
        'AssetManager',
        'EddieJaoude\\Zf2Logger',
        'BsbPhingService',
    	'Application',
        'Platform',
        'BI',
        'Core',
    	'ZF\Apigility\Admin',
    	'ZF\Configuration'
    ),
    'module_listener_options' => array(
        'module_paths' => array(
            './module',
            './vendor',
        ),
        'config_glob_paths' => array(
            'config/autoload/staging/{,*.}{global,local}.php',
        ),
    ),
);