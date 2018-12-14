<?php

namespace StarWars\Model;

use \StarWars\DB\Sql;
use \StarWars\Model;

class Planeta extends Model {

	/*
		Método que busca todos os registros no banco de dados
		Chamado através método listAll do PlanetaController
	*/
	public function listAll()
	{
		$sql = new Sql();

		return $sql->select("SELECT * FROM tb_planeta");
	}

	/*
		Método que adiciona um novo planeta no banco de dados
		Chamado através método create do PlanetaController
	*/
	public function create()
	{
		
		$sql = new Sql();

		$sql->query("SET CHARACTER SET utf8");

		$result = $sql->query("INSERT INTO tb_planeta 
								(nome, clima, terreno) 
								VALUES (:nome, :clima, 
								:terreno)", [
			':nome'				=> $this->getnome(),
			':clima'			=> $this->getclima(),
			':terreno'			=> $this->getterreno()
		]);

	}

	/*
		Método que seleciona um planeta buscando pelo ID
	*/
	public function get($planeta_id)
	{

		$sql = new Sql();

		$results = $sql->select("SELECT * FROM tb_planeta WHERE id = :planeta_id", array(
			":planeta_id" => $planeta_id
		));

		if($results) 
		{
			$this->setData($results[0]);
		}
	}

	/*
		Método que seleciona um planeta buscando pelo nome
	*/
	public function getPlaneta($nome_planeta)
	{

		$sql = new Sql();

		$results = $sql->select("SELECT * FROM tb_planeta WHERE nome = :nome_planeta", array(
			":nome_planeta" => $nome_planeta
		));

		if($results) 
		{
			$this->setData($results[0]);
		}
	}

	/*
		Método que deleta um planeta do banco de dados
		Chamado através método delete do PlanetaController
	*/
	public function delete() {

		$sql = new Sql();

		$sql->query("DELETE FROM tb_planeta
        				WHERE id = :planeta_id;", [
				':planeta_id' => $this->getid()
		]);

	}
}