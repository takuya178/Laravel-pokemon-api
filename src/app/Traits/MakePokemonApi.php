<?php

namespace App\Traits;

use GuzzleHttp\Client;

trait MakePokemonApi
{
  public function makeRequest($method, $requestUrl)
  {
    $pokemonList = [];
    $client = new Client();
    for ($i = 1; $i < 100; $i++) {
      $response = $client->request($method, $requestUrl.strval($i));
      $response = json_decode($response->getBody()->getContents());
      $pokemonList[] = [
        "図鑑番号" => $this->getGameIndex($response),
        "名前" => $this->getName($this->getSpeciesJson($response)),
        "タイプ" => $this->getName($this->getTypesJson($response)),
        "分類" => $this->getClassification($this->getSpeciesJson($response)),
        "重さ" => $this->getWeight($response),
        "説明文" => $this->getContext($this->getSpeciesJson($response)),
      ];
    }

    return var_dump($pokemonList);
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

  private function getContext($response)
  {
    return collect(json_decode($response)->flavor_text_entries)
      ->filter(fn($entry) => $entry->language->name === "ja-Hrkt")->pluck('flavor_text')[0];
  }
}
