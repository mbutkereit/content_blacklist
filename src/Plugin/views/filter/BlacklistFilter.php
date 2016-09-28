<?php

namespace Drupal\content_blacklist\Plugin\views\filter;

use Drupal\content_blacklist\BlacklistInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\views\Plugin\views\display\DisplayPluginBase;
use Drupal\views\Plugin\views\filter\BooleanOperator;
use Drupal\Core\Database\Query\Condition;
use Drupal\views\ViewExecutable;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Filters by given list of node title options.
 *
 * @ingroup views_filter_handlers
 *
 * @ViewsFilter("blacklist_filter")
 */
class BlacklistFilter extends BooleanOperator implements ContainerFactoryPluginInterface {

  private $blacklist;

  /**
   * Constructs a new LanguageFilter instance.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\content_blacklist\BlacklistInterface $blacklist
   *   The blacklist.
   */
  public function __construct($configuration, $plugin_id, $plugin_definition, BlacklistInterface $blacklist) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->blacklist = $blacklist;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('content_blacklist.blacklist')
    );
  }


  /**
   * {@inheritdoc}
   */
  public function init(ViewExecutable $view, DisplayPluginBase $display, array &$options = NULL) {
    parent::init($view, $display, $options);
    $this->valueTitle = $this->t('Blacklist');
  }

  /**
   * Override the query so that no filtering takes place if the user doesn't
   * select any options.
   */
  public function query() {
    // $this->blacklist->addToBlacklist('node',139103);.
    $blacklist = $this->blacklist->getBlacklist('node');
    if (!empty($blacklist)) {
      $condition = (new Condition('AND'))
        ->condition('nid', $blacklist, 'NOT IN');
      $this->query->addWhere($this->options['group'], $condition);
    }
  }

  /**
   * Skip validation if no options have been chosen so we can use it as a
   * non-filter.
   */
  public function validate() {
    if (!empty($this->value)) {
      parent::validate();
    }
  }

  /**
   *
   */
  public function canExpose() {
    return FALSE;
  }

}
