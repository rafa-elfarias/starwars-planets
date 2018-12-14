<?php 

namespace StarWars;

class Model {

	/*
		Classe pai para as models. Cria os getters e setters baseando-se no nome dos campos que estão sendo recebidos.
	*/

	public $values = [];

	public function __call($name, $args)
	{

		//Pula 3 caracteres para adicionar get ou set antes do nome do campo
		$method = substr($name, 0, 3);
		$fieldName = substr($name, 3, strlen($name));

		switch ($method)
		{

			case "get":
				return (isset($this->values[$fieldName])) ? $this->values[$fieldName] : NULL;
			break;

			case "set":
				$this->values[$fieldName] = $args[0];
			break;

		}

	}

	public function setData($data = array())
	{

		foreach ($data as $key => $value) {
			
			$this->{"set".$key}($value);

		}

	}

	public function getValues()
	{

		return $this->values;

	}

}

?>