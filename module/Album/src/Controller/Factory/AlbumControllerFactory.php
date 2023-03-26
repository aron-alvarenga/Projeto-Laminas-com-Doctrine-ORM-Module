<?php

namespace Album\Controller\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Album\Controller\AlbumController;

class AlbumControllerFactory
{
  public function __invoke(ContainerInterface $container)
  {
    return new AlbumController($container->get('Doctrine\ORM\EntityManager'));
  }
}
