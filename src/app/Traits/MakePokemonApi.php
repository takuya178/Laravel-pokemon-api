<?php

namespace App\Traits;

use GuzzleHttp\Client;

trait MakePokemonApi
{
  public function makeRequest($method, $requestUrl)
  {
    $client = new Client();
    $response = $client->request($method, $requestUrl);
    $response = json_decode($response->getBody()->getContents());
    return $this->getName($response);
  }

  public function getName($response)
  {
    $json = file_get_contents($response->species->url);
    $json = mb_convert_encoding($json, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');
    return $json;
    // return array_filter($response, function($res) {
    //   return $res->species->names->language->name = "ja-Hrkt";
    // });
  }
}
