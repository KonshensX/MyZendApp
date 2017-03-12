<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Profile\Controller\Profile' => 'Profile\Controller\ProfileController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'profile' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/profile[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Profile\Controller\Profile',
                        'action'     => 'index',
                    ),
                ),
            ),
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'profile' => __DIR__ . '/../view',
        ),
    ),
    'doctrine' => array(
        'driver' => array(

            'application_entities' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(__DIR__ . '/../src/Profile/Entity')
            ),

            'orm_default' => array(
                'drivers' => array(
                    'Profile\Entity' => 'application_entities',
                ),
            ),
        )
    )
);