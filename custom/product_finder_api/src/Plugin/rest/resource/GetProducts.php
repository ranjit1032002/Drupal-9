<?php

namespace Drupal\product_finder_api\Plugin\rest\resource;
use Drupal\rest\Plugin\ResourceBase;
use Drupal\rest\ResourceResponse;
use Drupal\node\Entity\Node;
use GuzzleHttp\client;
use Drupal\paragraphs\Entity\Paragraph;

/**
 * Provides a Demo Resource
 *
 * @RestResource(
 *   id = "products",
 *   label = @Translation("Get Products"),
 *   uri_paths = {
 *     "canonical" = "/get/products"
 *   }
 * )
 */
class GetProducts extends ResourceBase {
  /**
   * Responds to entity GET requests.
   * @return \Drupal\rest\ResourceResponse
   */

  public function get() {
    $nids = \Drupal::entityQuery('node')->condition('type','productfinder_question')->execute();
    $nodes =  Node::loadMultiple($nids);
    $response = $this->processNodes($nodes);
    return new ResourceResponse($response);
  }
  /**
   * Get Produts
   */

  private function processNodes($nodes){
    // $output= [];
    foreach ($nodes as $key=>$node){
      $output[$key][]=
      [
        'title'=> $node->get('title')->value,
        'field_before_content'=> $node->get('field_before_content')->value,
        'field_description'=> $node->get('field_description')->value,
        'field_answer_field'=>$this->processNestedNodes($node),
      ];
    }
    return $output;
  }

  private function processNestedNodes($node){
    $paragraph_fields=[];
    foreach ($node->field_answer_field as $reference) {
      $paragraph_fields[]=
      [
        'field_answer_ref_id'=>$reference->entity->field_answer_ref_id->value,
        'field_answer_title'=>$reference->entity->field_answer_title->value,
        'field_answer_type'=>$reference->entity->field_answer_type->value,
        'field_next_question'=>$reference->entity->field_next_question->value,
        'field_product_link'=>$reference->entity->field_product_link->value,
        'field_category_link'=>$reference->entity->field_category_link->value,
        'field_shade_selector'=>$reference->entity->field_shade_selector->value,
      ];
    }
    return $paragraph_fields;
  }

}
