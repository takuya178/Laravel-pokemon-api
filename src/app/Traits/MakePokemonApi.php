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
    $this->getGameIndex($response);
    $this->getName($this->getTypesJson($response));
    
  }

  public function getSpeciesJson($response)
  {
    return mb_convert_encoding(
      file_get_contents($response->species->url), 
      'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN'
      );
  }

  public function getTypesJson($response)
  {
    return mb_convert_encoding(
      file_get_contents($response->types[0]->type->url), 
      'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN'
      );
  }

  public function getName($response)
  {
    $result = collect(json_decode($response)->names)
      ->filter(fn($name) => $name->language->name === "ja-Hrkt")->pluck('name');
    return $result[0];
  }

  public function getGameIndex($response)
  {
    $result = collect($response->game_indices)
      ->filter(fn($index) => $index->version->name === "red")->pluck('game_index');
    return $result[0];
  }

}
