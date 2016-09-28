<?php

namespace Drupal\content_blacklist\Form;

use Drupal\Core\Entity\ContentEntityType;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class SettingsForm.
 *
 * @package Drupal\content_blacklist\Form
 */
class SettingsForm extends ConfigFormBase {

  const SETTINGS_FILENAME = 'content_blacklist.settings';

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $config = $this->config(self::SETTINGS_FILENAME)
      ->get('blacklisted_entities');
    $entity_types = \Drupal::entityTypeManager()->getDefinitions();
    $form['blacklisted'] = array(
      '#type' => 'details',
      '#title' => t('Items for the blacklist'),
      '#description' => t('some description'),
      '#open' => TRUE,
    );
    foreach ($entity_types as $key => $type) {
      if ($type instanceof ContentEntityType) {
        $form['blacklisted'][$key] = array(
          '#type' => 'checkbox',
          '#title' => 'Entity: ' . $key,
          '#default_value' => isset($config[$key]) ? $config[$key] : 0,
        );
      }
    }

    $form['pages'] = array(
      '#type' => 'textarea',
      '#title' => $this->t('Pages'),
      '#default_value' => $this->config(self::SETTINGS_FILENAME)
        ->get('ignore_blacklist_pages'),
      '#description' => $this->t("Specify pages by using their paths. Enter one path per line. The '*' character is a wildcard. Example paths are %user for the current user's page and %user-wildcard for every user page. %front is the front page.", array(
        '%user' => '/user',
        '%user-wildcard' => '/user/*',
        '%front' => '<front>',
      )),
    );

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $entity_types_blacklist_state = [];
    $entity_types = \Drupal::entityTypeManager()->getDefinitions();

    foreach ($entity_types as $key => $value) {
      if ($value instanceof ContentEntityType) {
        $entity_types_blacklist_state[$key] = $form_state->getValue($key);
      }
    }
    $this->configFactory()->getEditable(self::SETTINGS_FILENAME)
      ->set('ignore_blacklist_pages', $form_state->getValue('pages'))
      ->save();
    $this->configFactory()->getEditable(self::SETTINGS_FILENAME)
      ->set('blacklisted_entities', $entity_types_blacklist_state)
      ->save();

    parent::submitForm($form, $form_state);
  }

  /**
   * Gets the configuration names that will be editable.
   *
   * @return array
   *   An array of configuration object names that are editable if called in
   *   conjunction with the trait's config() method.
   */
  protected function getEditableConfigNames() {
    return [
      self::SETTINGS_FILENAME,
    ];
  }

  /**
   * Returns a unique string identifying the form.
   *
   * @return string
   *   The unique string identifying the form.
   */
  public function getFormId() {
    return 'content_blacklist.settings_form';
  }

}
