<?php

namespace Drupal\content_blacklist;

use Drupal\Component\Utility\Unicode;
use Drupal\content_blacklist\Form\SettingsForm;

/**
 * Class Blacklist.
 *
 * @package Drupal\content_blacklist
 */
class Blacklist implements BlacklistInterface {
  private $store;
  private $requestStack;
  private $aliasManager;
  private $currentPath;
  private $pathMatcher;

  private $state;

  /**
   *
   */
  public function __construct() {
    $this->requestStack = \Drupal::requestStack();
    $this->aliasManager = \Drupal::service('path.alias_manager');
    $this->currentPath = \Drupal::service('path.current');
    $this->pathMatcher = \Drupal::service('path.matcher');
    $this->state = NULL;
  }

  /**
   *
   */
  public function addToBlacklist($entity_type, $id) {
    if (!$this->isPathWithoutBlacklist()) {
      $this->store[$entity_type][$id] = $id;
    }
  }

  /**
   *
   */
  public function getBlacklist($entity_type) {
    if (!$this->isPathWithoutBlacklist()) {
      return $this->store[$entity_type];
    }
    return [];
  }

  /**
   *
   */
  private function isPathWithoutBlacklist() {
    if ($this->state === NULL) {
      $config = \Drupal::configFactory()
        ->get(SettingsForm::SETTINGS_FILENAME)
        ->get('ignore_blacklist_pages');
      $pages = Unicode::strtolower($config);
      if (!$pages) {
        $this->state = TRUE;
      }
      $request = $this->requestStack->getCurrentRequest();
      // Compare the lowercase path alias (if any) and internal path.
      $path = $this->currentPath->getPath($request);
      // Do not trim a trailing slash if that is the complete path.
      $path = $path === '/' ? $path : rtrim($path, '/');
      $path_alias = Unicode::strtolower($this->aliasManager->getAliasByPath($path));

      $this->state = $this->pathMatcher->matchPath($path_alias, $pages) || (($path != $path_alias) && $this->pathMatcher->matchPath($path, $pages));
    }
    return $this->state;
  }

}
