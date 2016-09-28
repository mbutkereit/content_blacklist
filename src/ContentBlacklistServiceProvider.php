<?php

namespace Drupal\content_blacklist;

use Drupal\Core\DependencyInjection\ContainerBuilder;
use Drupal\Core\DependencyInjection\ServiceProviderBase;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Modifies the language manager service.
 */
class ContentBlacklistServiceProvider extends ServiceProviderBase {

  /**
   * {@inheritdoc}
   */
  public function alter(ContainerBuilder $container) {
    if ($container->has('entity.query.sql')) {
      try {
        $container->getDefinitions();
        $definition = $container->getDefinition('entity.query.sql');
        $definition_old = clone $definition;
        $container->addDefinitions(['blacklisted.entity.query.sql' => $definition_old]);
        $definition->setClass('Drupal\content_blacklist\Entity\BlacklistQueryFactory');
        $definition->setArguments([new Reference('blacklisted.entity.query.sql')]);
      }
      catch (\Exception $e) {

      }
    }
  }

}
