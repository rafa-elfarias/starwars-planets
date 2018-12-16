<?php

class PlanetTest extends PHPUnit\Framework\TestCase
{
	/*
		Classe de teste para todas as rotas da API.
	*/

  private $http;

  public function setUp()
  {
    $this->http = new GuzzleHttp\Client(['base_uri' => 'http://localhost']);
  }

  public function tearDown() 
  {
    $this->http = null;
  }

  public function testGet()
	{
    $response = $this->http->request('GET', '/');

    $this->assertEquals(200, $response->getStatusCode());

    $contentType = $response->getHeaders()["Content-Type"][0];
    $this->assertEquals("application/json;charset=utf-8", $contentType);

    $result = json_decode($response->getBody(), true);
      $this->assertSame($result["message"], "Para jogar com planetas de Star Wars, bem-vindo você é.");
	}

	public function testPost_Planets()
  {
    $response = $this->http->request('POST', '/salvar/Bespin/temperate/gas giant');

    $this->assertEquals(200, $response->getStatusCode());

    $contentType = $response->getHeaders()["Content-Type"][0];
    $this->assertEquals("text/html; charset=UTF-8", $contentType);

    $result = json_decode($response->getBody(), true);
  }


  public function testPut_UpdateAll()
  {
    $response = $this->http->request('PUT', '/atualizar-planetas');

    $this->assertEquals(200, $response->getStatusCode());

    $contentType = $response->getHeaders()["Content-Type"][0];
    $this->assertEquals("text/html; charset=UTF-8", $contentType);

    $result = json_decode($response->getBody(), true);

  }

  public function testGet_ListAll()
  {
      $response = $this->http->request('GET', '/planetas');

    $this->assertEquals(200, $response->getStatusCode());

    $contentType = $response->getHeaders()["Content-Type"][0];
    $this->assertEquals("application/json;charset=utf-8", $contentType);

    $result = json_decode($response->getBody(), true);
  }

	public function testGet_PlanetById()
  {
    $response = $this->http->request('GET', '/planeta/1');

    $this->assertEquals(200, $response->getStatusCode());

    $contentType = $response->getHeaders()["Content-Type"][0];
    $this->assertEquals("application/json;charset=utf-8", $contentType);

    $result = json_decode($response->getBody(), true);
    $planet_name = $result["values"]["nome"];
    $json = file_get_contents("https://swapi.co/api/planets/?search=" .urlencode($planet_name));
    $response_api = json_decode($json);

    $num_of_films = 0;

    if($response_api->count > 0) 
    {
        $num_of_films = count($response_api->results[0]->films);
    }

   	$this->assertSame($result["values"]["id"], "1");
   	$this->assertSame($result["values"]["nome"], $planet_name);
   	$this->assertSame($result["values"]["clima"], "arid");
   	$this->assertSame($result["values"]["terreno"], "desert");
   	$this->assertSame($result["values"]["qtd_filmes"], (string)$num_of_films);
  }

  public function testGet_PlanetByNome()
  {
    $response = $this->http->request('GET', '/planeta/nome/Tatooine');

    $this->assertEquals(200, $response->getStatusCode());

    $contentType = $response->getHeaders()["Content-Type"][0];
    $this->assertEquals("application/json;charset=utf-8", $contentType);

      $result = json_decode($response->getBody(), true);
      $planet_name = $result["values"]["nome"];
      $json = file_get_contents("https://swapi.co/api/planets/?search=" .urlencode($planet_name));
    $response_api = json_decode($json);

    $num_of_films = 0;

    if($response_api->count > 0) 
    {
        $num_of_films = count($response_api->results[0]->films);
    }

   	$this->assertSame($result["values"]["id"], "1");
   	$this->assertSame($result["values"]["nome"], $planet_name);
   	$this->assertSame($result["values"]["clima"], "arid");
   	$this->assertSame($result["values"]["terreno"], "desert");
   	$this->assertSame($result["values"]["qtd_filmes"], (string)$num_of_films);
  }

  public function testDelete()
  {
    $response = $this->http->request('DELETE', '/deletar/3');

    $this->assertEquals(200, $response->getStatusCode());

    $contentType = $response->getHeaders()["Content-Type"][0];
    $this->assertEquals("text/html; charset=UTF-8", $contentType);

    $result = json_decode($response->getBody(), true);
      $this->assertSame($result["msg"], "Deletado com sucesso o planeta foi.");
	}

}