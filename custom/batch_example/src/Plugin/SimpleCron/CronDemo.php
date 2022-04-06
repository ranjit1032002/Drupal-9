<?php

namespace Drupal\batch_example\Plugin\SimpleCron;

use Drupal\simple_cron\Plugin\SimpleCronPluginBase;
use GuzzleHttp\client;

/**
 * Example cron job implementation.
 *
 * @SimpleCron(
 *   id = "cronjob",
 *   label = @Translation("Example cron job", context = "Simple cron")
 * )
 */
class CronDemo extends SimpleCronPluginBase {

  /**
   * Implement Process Function.
   */
  public function process(): void {
    $client = new Client();
    $response = $client->get('https://reqres.in/api/products');
    $result = json_decode($response->getBody(), TRUE);
    foreach ($result['data'] as $item) {
      _cron_example_create_node($item);
    }
  }

}
