<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

return array(
	'doctrine' => array(
		'driver' => array(
			'application_entities' => array(
				'class' =>'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
				'cache' => 'array',
				'paths' => array(__DIR__ . '/../src/Application/Entity')
			),
			'orm_default' => array(
				'drivers' => array(
					'Application\Entity' => 'application_entities'
				)
			)
		)
	),
    'router' => array(
        'routes' => array(
            'tools_setupsignup' => array(
        	'type' => 'Segment',
        	'options' => array(
                    'route' => '/tools/setupsignup/:clientcourse',
                    'constraints' => array(),
                    'defaults' => array(
        		'__NAMESPACE__' => 'Application\Controller',
        		'controller' => 'Tools',
                        'action' => 'setupsignup',
                    ),
        	),
                'may_terminate' => true,
            ),
            'tools_migratestudents' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/tools/migratestudents',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller' => 'Tools',
                        'action' => 'migratestudents',
                    ),
                ),
                'may_terminate' => true,
            ),
            'tools_migratesessions' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/tools/migratesessions',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller' => 'Tools',
                        'action' => 'migratesessions',
                    ),
                ),
                'may_terminate' => true,
            ),
            'tools_migrateanswers' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/tools/migrateanswers',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller' => 'Tools',
                        'action' => 'migrateanswers',
                    ),
                ),
                'may_terminate' => true,
            ),
            'tools_students' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/tools/students',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller' => 'Tools',
                        'action' => 'students',
                    ),
                ),
                'may_terminate' => true,
            ),
            'tools_dem' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/tools/dem',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller' => 'Tools',
                        'action' => 'dem',
                    ),
                ),
                'may_terminate' => true,
            ),        	
            'tools_testemail' => array(
                'type'	=> 'Segment',
                'options' => array(
                    'route' => '/tools/testdem/:email/:idsession',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller' => 'Tools',
                        'action' => 'testdem',
                    ),
                ),
                'may_terminate' => true,
            ),
            'tools_structure' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/tools/structure/:user/:course/:client',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller' => 'Tools',
                        'action' => 'structure',
                    ),
                ),
                'may_terminate' => true,
            ),
            'tools_structureall' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/tools/structureall/:course[/:delete]',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller' => 'Tools',
        		'action' => 'structureall',
                    ),
                ),
        	'may_terminate' => true,
            ),
            'exam_js' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/exam/ajcheckanswer/:optionid',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller' => 'Exam',
                        'action' => 'ajcheckanswer',
                    ),
                ),
                'may_terminate' => true,
            ),
            // Route for validating an url. parameter tkn is left intentionally optional for control purposes.
            'exam_tokenchallenge' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/exam/tokenchallenge[/][:tkn][/]',
                    'constraints' => array(
                        'tkn' => '[a-zA-Z0-9\.]+',
                    ),
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller' => 'Exam',
                        'action' => 'tokenchallenge',
                    ),
                ),
                'may_terminate' => true,
            ),        		
            // Route for validating an url. parameter tkn is left intentionally optional for control purposes.
            'exam_token' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/exam/token[/][:tkn][/]',
                    'constraints' => array(
                        'tkn' => '[a-zA-Z0-9\.]+',
                    ),
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller' => 'Exam',
                        'action' => 'token',
                    ),
                ),
                'may_terminate' => true,
            ),
            'exam_reset_demo' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/exam/reset/:id',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller' => 'Exam',
                        'action' => 'reset'	
                    ), 
                ),
                'may_terminate' => true,
            ),
            // Route per la gestione di un errore
            'exam_error' => array(
        	'type' => 'Segment',
        	'options' => array(
                    'route' => '/exam/error',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
        		'controller' => 'Exam',
        		'action' => 'error'
                    ),
        	),
        	'may_terminate' => true,
            ),
            'exam_nothing' => array(
        	'type' => 'Segment',
            	'options' => array(
                    'route' => '/exam/nothing',
                    'constraints' => array(),
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
        		'controller' => 'Exam',
        		'action' => 'nothing',
                    ),
        	),
        	'may_terminate' => true,
            ),
            'exam_challenges' => array(
                'type' => 'Segment',
        	'options' => array(
                    'route' => '/exam/challenges',
                    'constraints' => array(),
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller' => 'Exam',
                        'action' => 'challenges',
                    ),
                ),
                'may_terminate' => true,
            ),
            'exam_start' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/exam/start',
                    'constraints' => array(),
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller' => 'Exam',
                        'action' => 'start',
                    ),
                ),
                'may_terminate' => true,
            ),
            'exam_restart' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/exam/restart',
                    'constraints' => array(),
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller' => 'Exam',
                        'action' => 'restart',
                    ),
                ),
                'may_terminate' => true,
            ),
            'exam_saveanswer' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/exam/saveanswer/:optionValue',
                    'constraints' => array(),
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller' => 'Exam',
                        'action' => 'saveanswer',
                    ),
                ),
            ),
            'exam_end' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/exam/end[/:congrats]',
                    'constraints' => array(),
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller' => 'Exam',
                        'action' => 'end',
                    ),
                ),
            ),
            'exam_participate' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/exam/participate',
                    'constraints' => array(),
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller' => 'Exam',
                        'action' => 'participate',
                    ),
                ),
        	'may_terminate' => true,
            ),
            'exam_timeout' => array(
        	'type' => 'Segment',
                'options' => array(
                    'route' => '/exam/timeout',
                    'constraints' => array(),
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller' => 'Exam',
                        'action' => 'timeout',
                    ),
                ),
                'may_terminate' => true,
            ),
            'exam_root' => array(
                'type' => 'Segment',
            	'options' => array(
                    'route' => '/exam[/]',
                    'constraints' => array(),
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
        		'controller' => 'Exam',
        		'action' => 'index',
                    ),
        	),
                'may_terminate' => true,
            ),
            'signup_preform' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/signup/preform/:time/:clientcourse/:crc',
                    'constraints' => array(),
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller' => 'Signup',
                        'action' => 'preform',
                    ),
                ),
                'may_terminate' => true,
            ),
            'signup_form' => array(
                'type' => 'Segment',
                'options' => array(
                    //'route' => '/signup/form/[:params]:time/:clientcourse/:crc',
                    'route' => '/signup/form[:param]',
                    'constraints' => array(),
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller' => 'Signup',
                        'action' => 'form',
                    ),
                ),
                'may_terminate' => true,
            ),
            'signup_fbform' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/signup/fbform[/:state]',
                    'constraints' => array(),
                    'defaults' => array (
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller' => 'Signup',
                        'action' => 'fbform',    
                    ),
                ),
                'may_terminate' => true,
            ),
            'signup_landing' => array (
                'type' => 'Segment',
                'options' => array(
                    'route' => '/signup/landing/:coursename',
                    'constraints' => array(),
                    'defaults' => array(
                       '__NAMESPACE__' => 'Application\Controller',
                        'controller' => 'Signup',
                        'action' => 'landing'
                    ),
                ),
                'may_terminate' => true,
            ),
            'signup_registered' => array (
                'type' => 'Segment',
                'options' => array(
                    'route' => '/signup/registered/:coursename',
                    'constraints' => array(),
                    'defaults' => array(
                       '__NAMESPACE__' => 'Application\Controller',
                        'controller' => 'Signup',
                        'action' => 'registered'
                    ),
                ),
            ),
            'index' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller' => 'Index',
                        'action'     => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                ),
            ),
        ),
    ),
    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory',
        ),
        'aliases' => array(
            'translator' => 'MvcTranslator',
        ),
    	'factories' => array(
    		'ExamService' => 'Application\\Service\\ExamServiceFactory',
    		'StudentService' => 'Application\\Service\\StudentServiceFactory',
    		'CourseService' => 'Application\\Service\\CourseServiceFactory',
    		'ClientService' => 'Application\\Service\\ClientServiceFactory',
    	),
    ),
    'translator' => array(
        'locale' => 'en_US',
        'translation_file_patterns' => array(
            array(
                'type'     => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern'  => '%s.mo',
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Application\Controller\Index' => 'Application\Controller\IndexController',
        	'Application\Controller\Exam' => 'Application\Controller\ExamController',
        	'Application\Controller\Tools' => 'Application\Controller\ToolsController',
                'Application\Controller\Signup' => 'Application\Controller\SignupController'
        ),
    ),
    
    	'view_manager' => array(
		'default_suffix' => 'tpl', // <-- new option for path stack resolver
		'display_not_found_reason' => true,
		'display_exceptions'       => true,
		'doctype'                  => 'HTML5',
		'not_found_template'       => 'error/404',
		'exception_template'       => 'error/index',
		'template_map' => array(
			'layout/layout'           => __DIR__ . '/../view/layout/layout.tpl',
			'error/404'               => __DIR__ . '/../view/error/404.tpl',
			'error/index'             => __DIR__ . '/../view/error/index.tpl',
		),
		'template_path_stack' => array(
			__DIR__ . '/../view',
		),
		'strategies' => array(
			'ViewJsonStrategy'
		),
	),
	'session' => array(
		'remember_me_seconds' => 2419200,
		'use_cookies' => true,
		'cookie_httponly' => true,
	),
);
