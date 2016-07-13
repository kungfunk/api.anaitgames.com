<?php
namespace Api\Controller;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \Slim\Container as ContainerInterface;
use \Database\Dummy\Repositories\UsuarioRepository as UsuarioRepository;

class Usuarios
{
    protected $ci;

    public function __construct(ContainerInterface $ci) {
        $this->ci = $ci;
    }

    public function getPaginated(Request $request, Response $response, $arguments) {

        $repository = new UsuarioRepository;
        $usuarios = $repository->getAllPaginated();

        return $response
            ->withHeader("Content-Type", "application/json")
            ->withJson([
                "status" => "ok",
                "data" => $usuarios
            ]);
    }
}
