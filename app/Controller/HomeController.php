<?php 

namespace StarWars\Controller;

use Psr\Container\ContainerInterface;

class HomeController
{
    protected $container;

    // constructor receives container instance
    public function __construct(ContainerInterface $container) {
       $this->container = $container;
    }

    public function __invoke($req, $resp) {}

    public function index($request, $response) {
      return $response->withJson(['message' => 'Para jogar com planetas de Star Wars, bem-vindo você é.']);
      //return $response;
    }
}