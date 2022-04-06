<?php

namespace Drupal\table\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Implement Class.
 */
class TableController extends ControllerBase {

  /**
   * Implement Function.
   */
  public function show() {

    $build['Table_Form'] = \Drupal::formBuilder()->getForm('Drupal\table\Form\TableForm');

    $input = \Drupal::request()->query->get('input');
    $show_data = \Drupal::request()->query->get('show_data');

    $conn = \Drupal::database();
    $query = $conn->select('students', 'st');
    $query->fields('st', ['Id', 'Student_Name', 'Email']);

    if ($input) {
      $query->condition('st.Student_Name', '%' . $input . '%', 'LIKE');
    }
    if ($show_data) {
      $query->range(0, $show_data);
    }

    $result = $query->execute();

    $header = [
      'Id' => $this->t('S.No'),
      'Student_Name' => $this->t('Student Name'),
      'Email' => $this->t('Email Id'),
    ];

    $rows = [];
    foreach ($result as $res) {
      $rows[] = [
        $res->Id,
        $res->Student_Name,
        $res->Email,
      ];
    }

    $build['table'] = [
      '#type' => 'table',
      '#header' => $header,
      '#rows' => $rows,
      '#empty' => $this->t('No Content has Been Found'),
    ];

    return [
      '#type' => '#markup',
      '#markup' => render($build),
    ];
  }

}
