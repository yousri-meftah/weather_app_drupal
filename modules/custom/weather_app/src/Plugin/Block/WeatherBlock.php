<?php

namespace Drupal\weather_app\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use GuzzleHttp\Client;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\File\FileUrlGeneratorInterface;

/**
 * Provides a 'WeatherBlock' block.
 *
 * @Block(
 *   id = "weather_block",
 *   admin_label = @Translation("Weather Block"),
 * )
 */
class WeatherBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * The file URL generator service.
   *
   * @var \Drupal\Core\File\FileUrlGeneratorInterface
   */
  protected $fileUrlGenerator;

  /**
   * Constructs a new WeatherBlock instance.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin ID for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\File\FileUrlGeneratorInterface $file_url_generator
   *   The file URL generator service.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, FileUrlGeneratorInterface $file_url_generator) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->fileUrlGenerator = $file_url_generator;
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $city = 'istanbul'; // You can change this to a dynamic value if needed
    $api_key = 'AAHNJL5L46CESPNCUTSDVE2C2';
    $url = 'https://weather.visualcrossing.com/VisualCrossingWebServices/rest/services/timeline/' . $city . '?unitGroup=metric&key=' . $api_key . '&contentType=json';
    
    $client = new Client();
    $response = $client->get($url);
    $data = json_decode($response->getBody(), true);

    return [
      '#theme' => 'weather_template',
      '#city' => $city,
      '#weather' => $data,
      '#attached' => [
        'library' => [
          'weather_app/weather_app_styles',
        ],
      ],
      '#cache' => [
        'max-age' => 3600,
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('file_url_generator')
    );
  }
}

