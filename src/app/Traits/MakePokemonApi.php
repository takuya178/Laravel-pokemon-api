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
    return json_decode($response)->names[0]->name;
    // $a = collect(json_decode($response)->names);
    // return $a->filter(fn($names) => $names->language->name === "ja-Hrkt")->get('name');
    // return array_filter($response, function($res) {
    //   return $res->species->names->language->name = "ja-Hrkt";
    // });
  }
}
