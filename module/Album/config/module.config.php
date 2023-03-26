<?php

namespace Album;

use Laminas\Router\Http\Segment;

return [
  'router' => [
    'routes' => [
      'album' => [
        'type'    => Segment::class,
        'options' => [
          'route' => '/album[/:action[/:id]]',
          'constraints' => [
            'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
            'id'     => '[0-9]+',
          ],
          'defaults' => [
            'controller' => Controller\AlbumController::class,
            'action'     => 'index',
          ],
        ],
      ],
    ],
  ],

  'view_manager' => [
    'template_path_stack' => [
      'album' => __DIR__ . '/../view',
    ],
  ],

  'controllers' => [
    'factories' => [
      Controller\AlbumController::class => Controller\Factory\AlbumControllerFactory::class,
    ],
  ],

  'doctrine' => [
    'driver' => [
      __NAMESPACE__ . '_driver' => [
        'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
        'cache' => 'array',
        'paths' => [__DIR__ . '/../src/' . __NAMESPACE__ . '/Entity']
      ],
      'orm_default' => [
        'drivers' => [
          __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
        ]
      ]
    ]
  ],
];
