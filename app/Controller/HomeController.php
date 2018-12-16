<?php 

namespace StarWars\Controller;

use Psr\Container\ContainerInterface;

use \StarWars\API;

class HomeController
{
    protected $container;

    // constructor receives container instance
    public function __construct(ContainerInterface $container) {
       $this->container = $container;
    }

    public function __invoke($req, $resp) {}

    public function index($request, $response) {
      $api  = new API();
      $msg = $api->verifyAPI();

      return $response->withJson(['message' => $msg]);
    }
}