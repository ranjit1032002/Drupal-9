<?php

namespace Drupal\table\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Implement Class.
 */
class TableForm extends FormBase {

  /**
   * Implement getFormId.
   */
  public function getFormId() {
    return 'custom_table_form';
  }

  /**
   * Implement buildForm.
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $form_state->setMethod('GET')
      ->setAlwaysProcess(TRUE)
      ->setCached(FALSE)
      ->disableRedirect();

    $form['#cache'] = [
      'max-age' => 0,
    ];

    $form['input'] = [
      '#type' => 'textfield',
      '#value' => $this->getRequest()->query->get('input'),
    ];

    $values = [
      '0' => t('All'),
      '5' => t('5'),
      '10' => t('10'),
    ];

    $form['show_data'] = [
      '#type' => 'select',
      '#options' => $values,
      '#value' => $this->getRequest()->query->get('display-data'),
    ];

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => 'submit',
      '#name' => '',
    ];

    $form['#after_build'][] = [get_class($this), 'afterBuild'];

    return $form;
  }

  /**
   * Implement afterBuild.
   */
  public static function afterBuild(array $form, FormStateInterface $form_state) {
    unset($form['form_token']);
    unset($form['form_build_id']);
    unset($form['form_id']);
    return $form;
  }

  /**
   * Implement submitForm.
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    if ($this->getRequest()->query->get('text')) {
      $this->messenger()->addMessage($this->getRequest()->query->get('text'));
    }
  }

}
