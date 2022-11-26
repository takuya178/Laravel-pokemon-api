<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\MakePokemonService;

class PokemonController extends Controller
{
    public function index()
    {
        $service = new MakePokemonService();
        return $service->makeRequest('GET', 'https://pokeapi.co/api/v2/pokemon/');
    }
}
