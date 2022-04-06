<?php

namespace Drupal\product_finder_api\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Implement BasicPageController Class.
 */
class ProductFinderController extends ControllerBase {

  /**
   * Implement Function.
   */
  public function show() {
    return [
      '#theme' => 'product-finder-api',
      '#url'=>'https://www.gstatic.com/charts/loader.js',
    ];

  }
}
