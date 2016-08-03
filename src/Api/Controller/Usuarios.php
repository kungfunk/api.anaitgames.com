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
        $limit = 15;
        $page = isset($arguments["page"]) ? $arguments["page"] : 1;
        $sort_field = isset($arguments["sort_field"]) ? $arguments["sort_field"] : null;
        $sort_direction = isset($arguments["sort_direction"]) ? $arguments["sort_direction"] : null;
        $pagination = $this->usuarios_repository->getPaginationLinks($page, $limit);
        $usuarios = $this->usuarios_repository->findAllPaginated($page, $limit, $sort_field, $sort_direction);

        return $response
            ->withHeader("Content-Type", "application/json")
            ->withJson([
                "status" => "ok",
                "data" => $usuarios,
                "links" => $pagination
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
