<?php
namespace Api\Controller;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \Slim\Container as ContainerInterface;
use \Database\PDO\Repositories\UsuarioRepository as UsuarioRepository;

class Usuarios
{
    protected $ci;
    private $usuarios_repository;

    public function __construct(ContainerInterface $ci) {
        $this->ci = $ci;
        $this->usuarios_repository = new UsuarioRepository;
    }

    public function getPaginated(Request $request, Response $response, $arguments) {
        $usuarios = $this->usuarios_repository->findAllPaginated(1, 20);

        return $response
            ->withHeader("Content-Type", "application/json")
            ->withJson([
                "status" => "ok",
                "data" => $usuarios
            ]);
    }

    public function getUsuario(Request $request, Response $response, $arguments) {
        $id = $request->getAttribute("id");
        $usuario = $this->usuarios_repository->getById($id);

        return $response
            ->withHeader("Content-Type", "application/json")
            ->withJson([
                "status" => "ok",
                "data" => $usuario
            ]);
    }

    public function saveUsuario(Request $request, Response $response, $arguments) {

    }
}
