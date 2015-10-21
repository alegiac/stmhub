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
        	'tools_structure' => array(
        		'type' => 'Segment',
        		'options' => array(
        			'route' => '/tools/structure',
        			'defaults' => array(
        					'__NAMESPACE__' => 'Application\Controller',
        					'controller' => 'Tools',
        					'action' => 'structure',
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
        	'Application\Controller\Tools' => 'Application\Controller\ToolsController'
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
);
