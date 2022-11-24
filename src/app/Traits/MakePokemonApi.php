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
    return $this->getName($this->getSpeciesJson($response));
  }

  public function getSpeciesJson($response)
  {
    return mb_convert_encoding(
      file_get_contents($response->species->url), 
      'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN'
      );
  }

  public function getName($response)
  {
    $a = json_decode($response);
    $b = mb_convert_encoding($response, 'UTF-8', 'ASCII, JIS, UTF-8, SJIS');
    $c = json_decode($b);
    return $c->names[0]->name;
    // return array_filter($response, function($res) {
    //   return $res->species->names->language->name = "ja-Hrkt";
    // });
  }
}
