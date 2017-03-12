<?php

return array(
    'controllers' => array(
        'invokables' => array(
            'Post\Controller\Post' => \Post\Controller\PostController::class,
            'Post\Controller\Image' => \Post\Controller\ImageController::class,
        ),
    ),

    'router' => array(
        'routes' => array(
            'post' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/post[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Post\Controller\Post',
                        'action'     => 'index',
                    ),
                ),
            ),
            'image' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/image[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Post\Controller\Image',
                        'action'     => 'index',
                    ),
                ),
            ),
        ),
    ),

    'view_manager' => array(
        'template_path_stack' => array(
            'post' => __DIR__ . '/../view',
            'image' => __DIR__ . '/../view',
        ),
        'template_map' => array(
            'partial/search'           => __DIR__ . '/../view/partial/search.phtml',
        ),
    ),

    'service_manager' => array(
        'factories' => array(
            'AlbumTrackMapper' => function($sm) {
                $posttable = $sm->get('Post\Model\PostTable');
                $imagetable = $sm->get('Post\Model\ImageTable');

                $mapper = new Post\Model\PostImageMapper($$posttable, $imagetable);

                return $mapper;
            },
        ),
    ),
);