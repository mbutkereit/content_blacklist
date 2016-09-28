<?php

namespace Drupal\content_blacklist\Entity;

use Drupal\Core\Entity\Query\QueryInterface;

/**
 *
 */
class BlacklistQuery implements QueryInterface {
  private $query;

  /**
   * {@inheritdoc}
   */
  public function __construct(QueryInterface $query) {
    $this->query = $query;
  }

  /**
   * {@inheritdoc}
   */
  public function addTag($tag) {
    $this->query->addTag($tag);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function hasTag($tag) {
    return $this->query->hasTag($tag);
  }

  /**
   * {@inheritdoc}
   */
  public function hasAllTags() {
    return $this->query->hasAllTags();
  }

  /**
   * {@inheritdoc}
   */
  public function hasAnyTag() {
    return $this->query->hasAnyTag();
  }

  /**
   * {@inheritdoc}
   */
  public function addMetaData($key, $object) {
    $this->query->addMetaData($key, $object);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getMetaData($key) {
    return $this->query->getMetaData($key);
  }

  /**
   * {@inheritdoc}
   */
  public function __clone() {
    $this->query = clone $this->query;
  }

  /**
   * {@inheritdoc}
   */
  public function getEntityTypeId() {
    return $this->query->getEntityTypeId();
  }

  /**
   * {@inheritdoc}
   */
  public function condition($field, $value = NULL, $operator = NULL, $langcode = NULL) {
    $this->query->condition($field, $value, $operator, $langcode);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function exists($field, $langcode = NULL) {
    $this->query->exists($field, $langcode);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function notExists($field, $langcode = NULL) {
    $this->query->notExists($field, $langcode);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function pager($limit = 10, $element = NULL) {
    $this->query->pager($limit, $element);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function range($start = NULL, $length = NULL) {
    $this->query->range($start, $length);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function sort($field, $direction = 'ASC', $langcode = NULL) {
    $this->query->sort($field, $direction, $langcode);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function count() {
    $this->query->count();
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function tableSort(&$headers) {
    $this->query->tableSort($headers);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function execute() {
    \Drupal::moduleHandler()->alter('entity_query', $this);
    $result = $this->query->execute();
    \Drupal::moduleHandler()->alter('entity_query_result', $this, $result);
    return $result;
  }

  /**
   * {@inheritdoc}
   */
  public function andConditionGroup() {
    return $this->query->andConditionGroup();
  }

  /**
   * {@inheritdoc}
   */
  public function orConditionGroup() {
    return $this->query->orConditionGroup();
  }

  /**
   * {@inheritdoc}
   */
  public function accessCheck($access_check = TRUE) {
    $this->query->accessCheck($access_check);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function currentRevision() {
    $this->query->currentRevision();
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function allRevisions() {
    $this->query->allRevisions();
    return $this;
  }

}
