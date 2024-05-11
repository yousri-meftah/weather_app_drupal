<?php

namespace Drupal\weather_app\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a 'WeatherApp' block.
 *
 * @Block(
 *   id = "weather_app_block",
 *   admin_label = @Translation("Weather App Block"),
 * )
 */
class WeatherAppBlock extends BlockBase {
  /**
   * {@inheritdoc}
   */
  public function build() {
    $location = 'Istanbul';
    $weather_data = weather_app_get_weather($location);

    return [
      '#theme' => 'weather_app_block',
      '#location' => $location,
      '#temperature' => $weather_data['days'][0]['temp'],
    ];
  }
}

