<?php 

namespace StarWars;

class API {

	private $api ='https://swapi.co/api';
	private $api_search = 'https://swapi.co/api/planets/?search=';


	/*
		Esse método acessa um site externo verificando se ele está disponível para ser acessado
	*/
	public function verifyAPI()
    {
        if($this->isDomainAvailible($this->api)) 
        {
        	return 'Para jogar com planetas de Star Wars, bem-vindo você é.';
        } else {
        	return 'API indisponível.';
        }
    }

	/*
		Esse método acessa um site externo consumindo um serviço JSON.
		Colocado em uma classe separada para garantir a inversão de controle.
	*/
    public function getResponse($planeta)
    {
    	if($this->isDomainAvailible($this->api)) 
        {
        	
	        $json = file_get_contents($this->api_search . urlencode($planeta));

	        $response = json_decode($json);

		    $num_of_films = 0;

		    if($response->count > 0) 
		    {
		        $num_of_films = count($response->results[0]->films);
		    }

	        return $num_of_films;
	    } else {
        	return 'API indisponível.';
        }
    }

    /*
    	Retorna true, se o site estiver disponível, 
    	false se não estiver.
    */
    public function isDomainAvailible($domain)
    {
       //check, if a valid url is provided
       if(!filter_var($domain, FILTER_VALIDATE_URL))
       {
               return false;
       }

       //initialize curl
       $curlInit = curl_init($domain);
       curl_setopt($curlInit,CURLOPT_CONNECTTIMEOUT,10);
       curl_setopt($curlInit,CURLOPT_HEADER,true);
       curl_setopt($curlInit,CURLOPT_NOBODY,true);
       curl_setopt($curlInit,CURLOPT_RETURNTRANSFER,true);

       //get answer
       $response = curl_exec($curlInit);

       curl_close($curlInit);

       if ($response) return true;

       return false;
    }
}