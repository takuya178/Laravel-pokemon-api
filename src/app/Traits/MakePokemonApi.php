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
    $this->getClassification($this->getSpeciesJson($response));
    return $this->getWeight($response);
  }

  private function getSpeciesJson($response)
  {
    return mb_convert_encoding(
      file_get_contents($response->species->url), 
      'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN'
      );
  }

  private function getTypesJson($response)
  {
    return mb_convert_encoding(
      file_get_contents($response->types[0]->type->url), 
      'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN'
      );
  }

  private function getName($response)
  {
    return collect(json_decode($response)->names)
      ->filter(fn($name) => $name->language->name === "ja-Hrkt")->pluck('name')[0];
  }

  private function getGameIndex($response)
  {
    return collect($response->game_indices)
      ->filter(fn($index) => $index->version->name === "red")->pluck('game_index')[0];
  }

  private function getClassification($response)
  {
    return collect(json_decode($response)->genera)
    ->filter(fn($general) => $general->language->name === "ja-Hrkt")->pluck('genus')[0];
  }

  private function getWeight($response)
  {
    return $response->weight;
  }
}
