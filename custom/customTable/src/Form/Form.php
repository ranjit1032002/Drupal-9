<?php

namespace Drupal\customTable\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

// Use Drupal\Core\Database\Database;.

/**
 * Provides the form for adding countries.
 */
class Form extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'customform';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $form['Student_Name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Student Name'),
      '#required' => TRUE,
      '#maxlength' => 20,
      '#default_value' => '',
    ];
    $form['Email'] = [
      '#type' => 'email',
      '#title' => $this->t('Email Id'),
      '#required' => TRUE,
      '#maxlength' => 50,
      '#default_value' => '',
    ];

    $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#button_type' => 'primary',
      '#default_value' => $this->t('Save'),
    ];
    return $form;

  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    $field = $form_state->getValues();

    $fields["Student_Name"] = $field['Student_Name'];
    if (!$form_state->getValue('Student_Name') || empty($form_state->getValue('Student_Name'))) {
      $form_state->setErrorByName('Student_Name', $this->t('Provide First Name'));
    }

  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    try {
      // $conn = Database::getConnection();
      $conn = \Drupal::database();

      $field = $form_state->getValues();

      $fields["Student_Name"] = $field['Student_Name'];
      $fields["Email"] = $field['Email'];

      $conn->insert('students')
        ->fields($fields)->execute();
      \Drupal::messenger()->addMessage($this->t('The Student data has been succesfully saved'));

    }
    catch (Exception $ex) {
      \Drupal::logger('customTable')->error($ex->getMessage());
    }

  }

}
