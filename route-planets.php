<?php 

use \StarWars\Controller\APIController;
use \StarWars\Controller\HomeController;
use \StarWars\Controller\PlanetaController;

//Todas as rotas acessam um método no respectivo controle

//Rota principal onde poderia ficar a documentação da API
$app->get('/', HomeController::class . ':index');

//Rota que lista todos os planetas do banco de dados
$app->get('/planetas', PlanetaController::class . ':listAll');

//Rota para buscar planeta pelo ID
$app->get("/planeta/{id}", PlanetaController::class . ':readById');

//Rota para buscar planeta pelo nome
$app->get("/planeta/nome/{nome}", PlanetaController::class . ':readByName');

//Rota para adicionar um planeta com nome, clima e terreno
$app->post('/salvar/{nome}/{clima}/{terreno}', PlanetaController::class . ':create');

//Rota para deletar um planeta
$app->delete("/deletar/{id}", PlanetaController::class . ':delete');