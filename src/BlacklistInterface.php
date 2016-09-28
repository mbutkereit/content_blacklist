<?php
/**
 * Created by PhpStorm.
 * User: marvin
 * Date: 07/08/16
 * Time: 20:33
 */

namespace Drupal\content_blacklist;


interface BlacklistInterface {
  public function addToBlacklist($entity_type, $id);
  public function getBlacklist($entity_type);
}