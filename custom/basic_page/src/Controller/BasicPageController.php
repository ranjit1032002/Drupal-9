<?php

namespace Drupal\basic_page\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Implement BasicPageController Class.
 */
class BasicPageController extends ControllerBase {

  /**
   * Implement Function.
   */
  public function show() {
    $form = \Drupal::formBuilder()->getForm('Drupal\basic_page\Form\CustomForm');
    return [
      '#markup' => 'Basic Page',
    ];
  }

}
