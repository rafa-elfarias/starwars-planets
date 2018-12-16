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
		// Nova instancia da classe Sql
		$sql = new Sql();

		// Método select, da classe Sql, 
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
								(nome, clima, 
								terreno, qtd_filmes) 
								VALUES (:nome, :clima, 
								:terreno,:qtd_filmes)", [
			':nome'				=> $this->getnome(),
			':clima'			=> $this->getclima(),
			':terreno'			=> $this->getterreno(),
			':qtd_filmes'		=> $this->getqtd_filmes()
		]);

		if($result) 
		{
			echo json_encode ( array ('success' => true,'msg' => 'Salvo com sucesso o planeta foi.'), JSON_UNESCAPED_UNICODE);
		} else {
			echo json_encode ( array ('success' => false,'msg' => 'Não foi possível salvar o planeta.'), JSON_UNESCAPED_UNICODE);
		}	

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
		Método que atualiza um planeta do banco de dados
		Chamadono PlanetaController quando o número de filmes salvo está diferente do número mostrado pela API
	*/
	public function update() 
	{

		$sql = new Sql();

		$sql->query("UPDATE tb_planeta
        				SET qtd_filmes 	= :qtd_filmes
        				WHERE id = :planeta_id;", [
				':qtd_filmes'	=> $this->getqtd_filmes(),
				':planeta_id'	=> $this->getid()
		]);
	}

	public function updatedPlanet($planeta) 
	{

		$sql = new Sql();

		$result = $sql->query("UPDATE tb_planeta
        				SET qtd_filmes 	= :qtd_filmes
        				WHERE id = :planeta_id;", [
				':qtd_filmes'	=> $planeta["qtd_filmes"],
				':planeta_id'	=> $planeta["id"]
		]);

		if($result) 
		{
			echo json_encode ( array ('success' => true,'msg' => 'Planeta ' .$planeta["nome"]. ' atualizado com sucesso.'), JSON_UNESCAPED_UNICODE);
		} else {
			echo json_encode ( array ('success' => false,'msg' => 'Não foi possível atualizar os planetas.'), JSON_UNESCAPED_UNICODE);
		}
	}

	/*
		Método que deleta um planeta do banco de dados
		Chamado através método delete do PlanetaController
	*/
	public function delete() {

		$sql = new Sql();

		$result = $sql->query("DELETE FROM tb_planeta
    				WHERE id = :planeta_id;", [
			':planeta_id' => $this->getid()
		]);

		if($result) 
		{
			echo json_encode ( array ('success' => true,'msg' => 'Deletado com sucesso o planeta foi.'), JSON_UNESCAPED_UNICODE);
		} else {
			echo json_encode ( array ('success' => false,'msg' => 'Não foi possível deletar o planeta.'), JSON_UNESCAPED_UNICODE);
		}	
	}
}