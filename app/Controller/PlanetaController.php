<?php 

namespace StarWars\Controller;

use \StarWars\API;
use \StarWars\Model\Planeta;

class PlanetaController
{

  public function create($request, $response, $args) 
  {

    /*
      Instanciado um planeta através do model Planeta.
      A instância recebe todos valores através do método 
      setData que está presente na classe Model setando os 
      campos através dos respectivos nomes que está recebendo.
      O método create do Model Planeta é chamado para persistir 
      os dados.
    */
    $planeta = new Planeta();
    $planeta->setData($args);
    $planeta->create();

    return $response->withJson(['message' => 'Salvo com sucesso o planeta foi.']);  
  }

  public function delete($request, $response, $args)
  {

    /*
      Instanciado um planeta através do model Planeta.
      O registro é localizado através do método get que está presente na Model Planeta e seleciona o registro pelo ID passado pelo parâmetro $args['id'], com cast para integer.
      O método delete do Model Planeta é chamado para deleter 
      o registro.
    */
    $planeta = new Planeta();
    $planeta->get((int)$args['id']);
    $planeta->delete();

    return $response->withJson(['message' => 'Deletado com sucesso o planeta foi.']); 
  }

  /*
      Instanciado um planeta através do model Planeta.
      Verifica se existe pelo menos um registro na tabela.
      Se tiver um ou mais registros, percorre os registros adicionado uma contagem do número de filmes que o planeta apareceu consumindo um serviço de outro site.
      Insere a informação do número de filmes em um novo array e depois de percorrer todos os registros retorna um JSON com todos os dados (Nome, clima, terreno e número de filmes que o planeta apareceu.)
    */
  public function listAll($request, $response)
  {
    $list = new Planeta();
    $planetas = $list->listAll();

    if(count($planetas) > 0) 
    {

      $array_planetas = array();

      foreach ($planetas as $planeta) {

        $nome_planeta = $planeta["nome"];
        
        $films = $this->countFilms($nome_planeta);

        $planeta += ["Quantidade de filmes que apareceu: " => $films];

        array_push($array_planetas, $planeta);
      }
      
      header("Content-Type: application/json;charset=utf-8");
      echo json_encode($array_planetas, JSON_UNESCAPED_UNICODE);
      exit;

    } else {
      return $response->withJson(['message' => 'Nenhum planeta foi adicionado']);
    }
   
  }

  /*
    Instanciado um planeta através do model Planeta.
    O registro é localizado através do método get que está presente na Model Planeta e seleciona o registro pelo ID passado através do parâmetro $args['id'], com cast para integer.
    Caso registro existe, o método getValues retorna todos os valores desse registro e chama o método countFilms adicionada uma contagem do número de filmes que o planeta apareceu consumindo um serviço de outro site.
  */
  public function readById($request, $response, $args)
  {
    
    $planeta = new Planeta();
    $planeta->get((int)$args['id']);

    if($planeta->getValues()) 
    {

      $nome_planeta = $planeta->getValues()["nome"];

      $films = $this->countFilms($nome_planeta);

      $planeta->setData(["Quantidade de filmes que apareceu: " => $films]);

      header("Content-Type: application/json;charset=utf-8");
      echo json_encode($planeta, JSON_UNESCAPED_UNICODE);
      exit;

    } else {
      return $response->withJson(['message' => 'Nenhum planeta encontrado']);
    }
  }

  /*
    Instanciado um planeta através do model Planeta.
    O registro é localizado através do método get que está presente na Model Planeta e seleciona o registro pelo nome passado através do parâmetro $args['nome'], com cast para integer.
    Caso registro existe, o método getValues retorna todos os valores desse registro e chama o método countFilms adicionada uma contagem do número de filmes que o planeta apareceu consumindo um serviço de outro site.
  */
  public function readByName($request, $response, $args)
  {
    $planeta = new Planeta();
    $planeta->getPlaneta($args['nome']);

    if($planeta->getValues()) 
    {

      $nome_planeta = $planeta->getValues()["nome"];
      $films = $this->countFilms($nome_planeta);

      $planeta->setData(["Quantidade de filmes que apareceu: " => $films]);

      header("Content-Type: application/json;charset=utf-8");
      echo json_encode($planeta, JSON_UNESCAPED_UNICODE);
      exit;

    } else {

      return $response->withJson(['message' => 'Nenhum planeta encontrado']);
    }
  }

  /*
    Esse método consome um serviço de outro site para contar o número de vezes que um planeta apareceu em filmes.
  */
  public function countFilms($nome_planeta)
  {
    
    $api  = new API();
    $json = $api->getResponse($nome_planeta);
    
    $response = json_decode($json);

    $num_of_films = 0;

    if($response->count > 0) 
    {
        $num_of_films = count($response->results[0]->films);
    }

    return $num_of_films;
  }
}