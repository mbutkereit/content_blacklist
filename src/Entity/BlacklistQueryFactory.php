<?php

namespace Drupal\content_blacklist\Entity;

use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Entity\Query\QueryFactoryInterface;

/**
 *
 */
class BlacklistQueryFactory implements QueryFactoryInterface {

  private $queryFactory;

  /**
   *
   */
  public function __construct(QueryFactoryInterface $factory) {
    $this->queryFactory = $factory;
  }

  /**
   *
   */
  public function get(EntityTypeInterface $entity_type, $conjunction) {
    return new BlacklistQuery($this->queryFactory->get($entity_type, $conjunction));
  }

  /**
   *
   */
  public function getAggregate(EntityTypeInterface $entity_type, $conjunction) {
    return $this->queryFactory->getAggregate($entity_type, $conjunction);
  }

}
