<?php
return array(
    'router' => array(
        'routes' => array(),
    ),
    'zf-versioning' => array(
        'uri' => array(),
    ),
    'service_manager' => array(
        'factories' => array(),
    ),
    'zf-rest' => array(),
    'zf-content-negotiation' => array(
        'controllers' => array(),
        'accept_whitelist' => array(),
        'content_type_whitelist' => array(),
    ),
    'zf-hal' => array(
        'metadata_map' => array(),
    ),
    'controllers' => array(
        'factories' => array(),
    ),
    'zf-rpc' => array(),
    'zf-content-validation' => array(),
    'input_filter_specs' => array(
        'BI\\V1\\Rpc\\GetUserWidgets\\Validator' => array(
            0 => array(
                'required' => true,
                'validators' => array(),
                'filters' => array(),
                'name' => 'structureId',
                'description' => 'Structure identifier for which the user needs to retrieve Widgets',
            ),
        ),
        'BI\\V1\\Rpc\\GetWidgetListByUserAndStructure\\Validator' => array(
            0 => array(
                'required' => true,
                'validators' => array(),
                'filters' => array(),
                'name' => 'structureId',
            ),
        ),
        'BI\\V1\\Rpc\\LoadPageStructure\\Validator' => array(
            0 => array(
                'required' => true,
                'validators' => array(),
                'filters' => array(),
                'name' => 'page_id',
                'description' => 'Page unique identifier',
                'error_message' => 'Page identifier is not set or contains not correct values.',
            ),
        ),
    ),
);
