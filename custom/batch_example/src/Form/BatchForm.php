<?php

namespace Drupal\batch_example\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\file\Entity\File;
use Drupal\Core\Url;

/**
 * Start of the class.
 */
class BatchForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'BatchCreationForm';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $form['import_csv'] = [
      '#type' => 'managed_file',
      '#title' => t('Upload file here'),
      '#upload_location' => 'public://importcsv/',
      '#default_value' => '',
      '#upload_validators' => ["file_validate_extensions" => ["csv"]],
      '#states' => [
        'visible' => [
          ':input[name="File_type"]' => ['value' => t('Upload your file')],
        ],
      ],
    ];

    $cron_url = Url::fromRoute('system.cron', ['key' => \Drupal::state()->get('system.cron_key')], ['absolute' => TRUE])->toString() . '?job=cronjob&force=1';
    $form['show_link'] = [
      '#prefix' => '<a href=' . $cron_url . '>',
      '#suffix' => '</a>',
      '#markup' => t('Click Here to run Cron Job'),
      '#weight' => -100,
    ];

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => 'Go',
    ];
    return $form;
  }

  /**
   * Submitting a form.
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $csv_file = $form_state->getValue('import_csv');
    $file = File::load($csv_file[0]);
    $file->setPermanent();
    $file->save();
    $data = $this->csvtoarray($file->getFileUri(), ',');
    foreach ($data as $row) {
      $operations[] = ['addImportContentItem', [$row]];
    }
    $batch = [
      'title' => t('Importing Data...'),
      'operations' => $operations,
      'init_message' => t('Import is starting.'),
      'finished' => 'addImportContentItemCallback',
    ];
    batch_set($batch);
  }

  /**
   * Implement Function csvtoarray.
   */
  public function csvtoarray($filename, $delimeter) {
    if (!file_exists($filename) || !is_readable($filename)) {
      return FALSE;
    }
    $header = NULL;
    $data = [];
    if (($handle = fopen($filename, 'r')) != FALSE) {
      while (($row = fgetcsv($handle, 10, $delimeter)) != FALSE) {
        if (!$header) {
          $header = $row;
        }
        else {
          $data[] = array_combine($header, $row);
        }
      }
      fclose($handle);
    }
    return $data;
  }

}
