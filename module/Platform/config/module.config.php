<?php
return array(
    'service_manager' => array(
        'factories' => array(
            'NavigationService' => 'Platform\\Service\\NavigationServiceFactory',
        ),
    ),
    'doctrine' => array(
        'driver' => array(
            'Platform_driver' => array(
                'class' => 'Doctrine\\ORM\\Mapping\\Driver\\AnnotationDriver',
                'cache' => 'array',
                'paths' => __DIR__ . '/../src/Platform/Entity',
            ),
            'orm_default' => array(
                'drivers' => array(
                    'Platform\\Entity' => 'Platform_driver',
                ),
            ),
        ),
    ),
    'controllers' => array(
        'factories' => array(
            'Platform\\V1\\Rpc\\GetPageByName\\Controller' => 'Platform\\V1\\Rpc\\GetPageByName\\GetPageByNameControllerFactory',
            'Platform\\V1\\Rpc\\GetMenuItems\\Controller' => 'Platform\\V1\\Rpc\\GetMenuItems\\GetMenuItemsControllerFactory',
            'Platform\\V1\\Rpc\\GetPages\\Controller' => 'Platform\\V1\\Rpc\\GetPages\\GetPagesControllerFactory',
            'Platform\\V1\\Rpc\\GetWidgets\\Controller' => 'Platform\\V1\\Rpc\\GetWidgets\\GetWidgetsControllerFactory',
            'Platform\\V1\\Rpc\\GetWidgetByName\\Controller' => 'Platform\\V1\\Rpc\\GetWidgetByName\\GetWidgetByNameControllerFactory',
        ),
    ),
    'router' => array(
        'routes' => array(
            'platform.rpc.get-page-by-name' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/platform/page/byname/[:code]',
                    'defaults' => array(
                        'controller' => 'Platform\\V1\\Rpc\\GetPageByName\\Controller',
                        'action' => 'getPageByName',
                    ),
                ),
            ),
            'platform.rpc.get-menu-items' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/platform/menuitems',
                    'defaults' => array(
                        'controller' => 'Platform\\V1\\Rpc\\GetMenuItems\\Controller',
                        'action' => 'getMenuItems',
                    ),
                ),
            ),
            'platform.rpc.get-pages' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/platform/pages',
                    'defaults' => array(
                        'controller' => 'Platform\\V1\\Rpc\\GetPages\\Controller',
                        'action' => 'getPages',
                    ),
                ),
            ),
            'platform.rpc.get-widgets' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/platform/widgets',
                    'defaults' => array(
                        'controller' => 'Platform\\V1\\Rpc\\GetWidgets\\Controller',
                        'action' => 'getWidgets',
                    ),
                ),
            ),
            'platform.rpc.get-widget-by-name' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/platform/widget/byname/[:code]',
                    'defaults' => array(
                        'controller' => 'Platform\\V1\\Rpc\\GetWidgetByName\\Controller',
                        'action' => 'getWidgetByName',
                    ),
                ),
            ),
        ),
    ),
    'zf-versioning' => array(
        'uri' => array(
            5 => 'platform.rpc.get-page-by-name',
            7 => 'platform.rpc.get-menu-items',
            8 => 'platform.rpc.get-pages',
            0 => 'platform.rpc.get-widgets',
            9 => 'platform.rpc.get-widget-by-name',
        ),
        'default_version' => 1,
    ),
    'zf-rpc' => array(
        'Platform\\V1\\Rpc\\GetPageByName\\Controller' => array(
            'service_name' => 'GetPageByName',
            'http_methods' => array(
                0 => 'GET',
            ),
            'route_name' => 'platform.rpc.get-page-by-name',
        ),
        'Platform\\V1\\Rpc\\GetMenuItems\\Controller' => array(
            'service_name' => 'GetMenuItems',
            'http_methods' => array(
                0 => 'GET',
            ),
            'route_name' => 'platform.rpc.get-menu-items',
        ),
        'Platform\\V1\\Rpc\\GetPages\\Controller' => array(
            'service_name' => 'GetPages',
            'http_methods' => array(
                0 => 'GET',
            ),
            'route_name' => 'platform.rpc.get-pages',
        ),
        'Platform\\V1\\Rpc\\GetWidgets\\Controller' => array(
            'service_name' => 'GetWidgets',
            'http_methods' => array(
                0 => 'GET',
            ),
            'route_name' => 'platform.rpc.get-widgets',
        ),
        'Platform\\V1\\Rpc\\GetWidgetByName\\Controller' => array(
            'service_name' => 'GetWidgetByName',
            'http_methods' => array(
                0 => 'GET',
            ),
            'route_name' => 'platform.rpc.get-widget-by-name',
        ),
    ),
    'zf-content-negotiation' => array(
        'controllers' => array(
            'Platform\\V1\\Rpc\\GetPageByName\\Controller' => 'Json',
            'Platform\\V1\\Rpc\\GetMenuItems\\Controller' => 'Json',
            'Platform\\V1\\Rpc\\GetPages\\Controller' => 'Json',
            'Platform\\V1\\Rpc\\GetWidgets\\Controller' => 'Json',
            'Platform\\V1\\Rpc\\GetWidgetByName\\Controller' => 'Json',
        ),
        'accept_whitelist' => array(
            'Platform\\V1\\Rpc\\GetPageByName\\Controller' => array(
                0 => 'application/vnd.platform.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ),
            'Platform\\V1\\Rpc\\GetMenuItems\\Controller' => array(
                0 => 'application/vnd.platform.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ),
            'Platform\\V1\\Rpc\\GetPages\\Controller' => array(
                0 => 'application/vnd.platform.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ),
            'Platform\\V1\\Rpc\\GetWidgets\\Controller' => array(
                0 => 'application/vnd.platform.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ),
            'Platform\\V1\\Rpc\\GetWidgetByName\\Controller' => array(
                0 => 'application/vnd.platform.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ),
        ),
        'content_type_whitelist' => array(
            'Platform\\V1\\Rpc\\GetPageByName\\Controller' => array(
                0 => 'application/vnd.platform.v1+json',
                1 => 'application/json',
            ),
            'Platform\\V1\\Rpc\\GetMenuItems\\Controller' => array(
                0 => 'application/vnd.platform.v1+json',
                1 => 'application/json',
            ),
            'Platform\\V1\\Rpc\\GetPages\\Controller' => array(
                0 => 'application/vnd.platform.v1+json',
                1 => 'application/json',
            ),
            'Platform\\V1\\Rpc\\GetWidgets\\Controller' => array(
                0 => 'application/vnd.platform.v1+json',
                1 => 'application/json',
            ),
            'Platform\\V1\\Rpc\\GetWidgetByName\\Controller' => array(
                0 => 'application/vnd.platform.v1+json',
                1 => 'application/json',
            ),
        ),
    ),
    'zf-mvc-auth' => array(
        'authorization' => array(),
    ),
    'zf-content-validation' => array(
        'Platform\\V1\\Rpc\\GetPageByName\\Controller' => array(
            'input_filter' => 'Platform\\V1\\Rpc\\GetPageByName\\Validator',
        ),
    ),
    'input_filter_specs' => array(
        'Platform\\V1\\Rpc\\GetUserWidgets\\Validator' => array(
            0 => array(
                'required' => true,
                'validators' => array(),
                'filters' => array(),
                'name' => 'structureId',
                'description' => 'User\'s structureId',
            ),
        ),
        'Platform\\V1\\Rpc\\ConfigurationDump\\Validator' => array(
            0 => array(
                'required' => true,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\Validator\\Regex',
                        'options' => array(
                            'pattern' => '(json|array)',
                        ),
                    ),
                ),
                'filters' => array(),
                'name' => 'format',
                'description' => 'Type of required format (json, object dump)',
            ),
        ),
        'Platform\\V1\\Rpc\\PIppo\\Validator' => array(
            0 => array(
                'required' => true,
                'validators' => array(),
                'filters' => array(),
                'name' => 'aaa',
                'allow_empty' => true,
            ),
        ),
        'Platform\\V1\\Rpc\\LoadPageStructure\\Validator' => array(
            0 => array(
                'required' => true,
                'validators' => array(),
                'filters' => array(),
                'name' => 'page_id',
                'description' => 'Page unique identifier',
            ),
        ),
        'Platform\\V1\\Rpc\\GetPage\\Validator' => array(
            0 => array(
                'required' => true,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\Alpha',
                        'options' => array(
                            'allowwhitespace' => '',
                            'breakchainonfailure' => true,
                        ),
                    ),
                    1 => array(
                        'name' => 'Zend\\Validator\\NotEmpty',
                        'options' => array(
                            'breakchainonfailure' => true,
                        ),
                    ),
                ),
                'filters' => array(),
                'name' => 'id',
                'description' => 'Page identifier',
                'error_message' => 'Parameter "id" is mandatory',
            ),
        ),
        'Platform\\V1\\Rpc\\GetWidget\\Validator' => array(),
        'Platform\\V1\\Rpc\\GetWidgetByName\\Validator' => array(
            0 => array(
                'required' => true,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\Alpha',
                        'options' => array(
                            'breakchainonfailure' => true,
                            'allowwhitespace' => '',
                        ),
                    ),
                    1 => array(
                        'name' => 'Zend\\Validator\\NotEmpty',
                        'options' => array(
                            'breakchainonfailure' => true,
                        ),
                    ),
                ),
                'filters' => array(),
                'name' => 'code',
                'description' => 'Widget name',
                'error_message' => 'Param "code" is mandatory',
            ),
        ),
        'Platform\\V1\\Rpc\\GetPageByName\\Validator' => array(
            0 => array(
                'required' => true,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\Validator\\NotEmpty',
                        'options' => array(
                            'breakchainonfailure' => true,
                        ),
                    ),
                    1 => array(
                        'name' => 'Zend\\I18n\\Validator\\Alpha',
                        'options' => array(
                            'breakchainonfailure' => true,
                            'allowwhitespace' => '',
                        ),
                    ),
                ),
                'filters' => array(),
                'name' => 'code',
                'description' => 'Page name',
                'error_message' => 'Param "code" is mandatory',
            ),
        ),
    ),
    'console' => array(
        'router' => array(
            'routes' => array(
                'console-dispatcher' => array(
                    'options' => array(
                        'route' => 'platform setup (db|seed|entities):action',
                        'defaults' => array(
                            '__NAMESPACE__' => 'Platform\\Controller',
                            'controller' => 'Console',
                            'action' => 'dispatch',
                        ),
                    ),
                ),
            ),
        ),
    ),
);
