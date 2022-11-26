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
    $this->getName($this->getSpeciesJson($response));
    return $this->getGameIndex($response);
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
    // $b = $a->filter(fn($names) => $names->language->name === "ja-Hrkt")->pluck('name');
    // return json_encode($b, JSON_UNESCAPED_UNICODE);
    // return array_filter($response, function($res) {
    //   return $res->species->names->language->name = "ja-Hrkt";
    // });
  }

  public function getGameIndex($response)
  {
    $filterGeneral = (array_filter($response->game_indices, function ($index) {
      return $index->version->name === "red";
    }));
    return $filterGeneral[0]->game_index;
  }
}
