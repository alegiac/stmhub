<?php
return array(
    'controllers' => array(
        'factories' => array(
            'Core\\V1\\Rpc\\Heartbeat\\Controller' => 'Core\\V1\\Rpc\\Heartbeat\\HeartbeatControllerFactory',
            'Core\\V1\\Rpc\\ConfigurationDump\\Controller' => 'Core\\V1\\Rpc\\ConfigurationDump\\ConfigurationDumpControllerFactory',
        ),
    ),
    'router' => array(
        'routes' => array(
            'core.rpc.heartbeat' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/core/heartbeat',
                    'defaults' => array(
                        'controller' => 'Core\\V1\\Rpc\\Heartbeat\\Controller',
                        'action' => 'heartbeat',
                    ),
                ),
            ),
            'core.rpc.configuration-dump' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/core/configuration-dump/format/[:format]',
                    'defaults' => array(
                        'controller' => 'Core\\V1\\Rpc\\ConfigurationDump\\Controller',
                        'action' => 'configurationDump',
                    ),
                ),
            ),
        ),
    ),
    'zf-versioning' => array(
        'uri' => array(
            0 => 'core.rpc.heartbeat',
            1 => 'core.rpc.configuration-dump',
        ),
    ),
    'zf-rpc' => array(
        'Core\\V1\\Rpc\\Heartbeat\\Controller' => array(
            'service_name' => 'Heartbeat',
            'http_methods' => array(
                0 => 'GET',
            ),
            'route_name' => 'core.rpc.heartbeat',
        ),
        'Core\\V1\\Rpc\\ConfigurationDump\\Controller' => array(
            'service_name' => 'ConfigurationDump',
            'http_methods' => array(
                0 => 'GET',
            ),
            'route_name' => 'core.rpc.configuration-dump',
        ),
    ),
    'zf-content-negotiation' => array(
        'controllers' => array(
            'Core\\V1\\Rpc\\Heartbeat\\Controller' => 'Json',
            'Core\\V1\\Rpc\\ConfigurationDump\\Controller' => 'Json',
        ),
        'accept_whitelist' => array(
            'Core\\V1\\Rpc\\Heartbeat\\Controller' => array(
                0 => 'application/vnd.core.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ),
            'Core\\V1\\Rpc\\ConfigurationDump\\Controller' => array(
                0 => 'application/vnd.core.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ),
        ),
        'content_type_whitelist' => array(
            'Core\\V1\\Rpc\\Heartbeat\\Controller' => array(
                0 => 'application/vnd.core.v1+json',
                1 => 'application/json',
            ),
            'Core\\V1\\Rpc\\ConfigurationDump\\Controller' => array(
                0 => 'application/vnd.core.v1+json',
                1 => 'application/json',
            ),
        ),
    ),
);
