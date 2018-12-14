<?php 

namespace StarWars;

class API {

	/*
		Esse método acessa um site externo consumindo um serviço JSON.
		Colocado em uma classe separada para garantir a inversão de controle.
	*/
    public function getResponse($planeta)
    {
        
        $response = file_get_contents("https://swapi.co/api/planets/?search=" .urlencode($planeta));

        return $response;
    }

}