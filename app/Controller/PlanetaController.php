<?php 

namespace StarWars\Controller;

use \StarWars\API;
use \StarWars\Model\Planeta;

class PlanetaController
{

  /*
    Instanciado um planeta através do model Planeta.
    A instância recebe todos valores através do método 
    setData que está presente na classe Model setando os 
    campos através dos respectivos nomes que está recebendo.
    O método create do Model Planeta é chamado para persistir 
    os dados.
  */
  public function create($request, $response, $args) 
  {

    $planeta = new Planeta();
    $planeta->setData($args);

    $nome_planeta = $planeta->getValues()["nome"];
    $films = $this->countFilms($nome_planeta);
    
    $planeta->setData(["qtd_filmes" => $films]);
    $planeta->create();
  }

  /*
    Instanciado um planeta através do model Planeta.
    O registro é localizado através do método get que está presente na Model Planeta e seleciona o registro pelo ID passado pelo parâmetro $args['id'], com cast para integer.
    O método delete do Model Planeta é chamado para deleter 
    o registro.
  */
  public function delete($request, $response, $args)
  {

    $planeta = new Planeta();
    $planeta->get((int)$args['id']);
    $planeta->delete();
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

      header("Content-Type: application/json;charset=utf-8");
      echo json_encode($planetas, JSON_UNESCAPED_UNICODE);
      exit;

    } else {
      return $response->withJson(['message' => 'Nenhum planeta foi adicionado']);
    }
  }

  /*
    Instanciado um planeta através do model Planeta.
    Verifica se existe pelo menos um registro na tabela.
    Se tiver um ou mais registros, percorre os registros verificando a contagem do número de filmes que o planeta apareceu consumindo um serviço de outro site.
    Se a contagem do número de filmes estiver diferente, atualiza o banco de dados com o número obtido através do site em que a API se conectou
  */
  public function updateAll($request, $response)
  {
    $list = new Planeta();
    $planetas = $list->listAll();

    if(count($planetas) > 0) 
    {

      foreach ($planetas as $planeta) {

        $nome_planeta = $planeta["nome"];
        
        $qtd_films_api = $this->countFilms($nome_planeta);
        $qtd_films     = $planeta["qtd_filmes"];
        
        if($qtd_films_api != $qtd_films) 
        {
          $planeta["qtd_filmes"] = $qtd_films_api;

          $planet_updated = new Planeta();
          $planet_updated->updatedPlanet($planeta);
        }
      }

      //return $response->withJson(['message' => 'Atualizados com sucesso os planetas foram.']);
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

      $qtd_films_api  = (int)$this->countFilms($nome_planeta);
      $qtd_films      = (int)$planeta->getValues()["qtd_filmes"];
        
      if($qtd_films_api != $qtd_films) 
      {
        $planeta->setData(["qtd_filmes"=> $qtd_films_api]);
        $planeta->update();
      }

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

      $qtd_films_api  = (int)$this->countFilms($nome_planeta);
      $qtd_films      = (int)$planeta->getValues()["qtd_filmes"];
        
      if($qtd_films_api != $qtd_films) 
      {
        $planeta->setData(["qtd_filmes"=> $qtd_films_api]);
        $planeta->update();
      }

      /*$planeta->setData(["Quantidade de filmes que apareceu: " => $films]);*/

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
    $num_of_films = $api->getResponse($nome_planeta);

    return $num_of_films;
  }
}