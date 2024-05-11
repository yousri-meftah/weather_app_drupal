<?php

namespace Drupal\weather_app\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\JsonResponse;
use GuzzleHttp\Client;

class WeatherController extends ControllerBase {

  public function getWeather($city) {
    $api_key = 'AAHNJL5L46CESPNCUTSDVE2C2';
    $url = 'https://weather.visualcrossing.com/VisualCrossingWebServices/rest/services/timeline/' . $city . '?unitGroup=metric&key=' . $api_key . '&contentType=json';
    
    $client = new Client();
    $response = $client->get($url);
    $data = json_decode($response->getBody(), true);

    if ($response->getStatusCode() === 200) {
      return [
        '#theme' => 'weather_template',
        '#city' => $city,
        '#weather' => $data,
      ];
    } else {
      return new JsonResponse(['error' => 'Unable to fetch weather data.'], 500);
    }
  }
}

