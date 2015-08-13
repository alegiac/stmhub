<?php
use Zend\Mvc\Router\Http\Literal;
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
            'home' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action'     => 'index',
                    ),
                ),
            ),
        	
        	'exam_token' => array(
        		'type' => 'Segment',
        		'options' => array(
        			'route' => '/exam/token/:tkn[/]',
        			'constraints' => array(
        				'tkn' => '[a-zA-Z0-9\.]+',
        			),
        			'defaults' => array(
        				'__NAMESPACE__' => 'Application\Controller',
        				'controller' => 'Exam',
        				'action' => 'index',
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
        	'exam_exception' => array(
        		'type' => 'Segment',
        		'options' => array(
        			'route' => '/exam/exception',
        			'default' => array(
        				'__NAMESPACE__' => 'Application\Controller',
        				'controller' => 'Exam',
        				'action' => 'exception'
        			),
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
	),
);
