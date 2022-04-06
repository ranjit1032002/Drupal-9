<?php

namespace Drupal\basic_page\Plugin\Block;

use Drupal\user\Entity\User;
use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'Hello' Block.
 *
 * @Block(
 *   id = "hello_block",
 *   admin_label = @Translation("Hello block"),
 *   category = @Translation("Hello World"),
 * )
 */
class HelloBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $name = '';
    $user = User::load(\Drupal::currentUser()->id());
    if (!empty($user)) {
      $name = $user->get('name')->value;
    }
    return [
      '#markup' => 'Hello, World!:-' . $name,
    ];
  }

}
// Use Drupal\Core\Entity\Display\Entity\EntityViewDisplayInterface;
// use Drupal\Core\Entity\EntityInterface;
// use Drupal\Core\Form\FormStateInterface;
// use Drupal\node\NodeInterface;
// use Drupal\Core\Routing\RoutMatchInterface;.
