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

    public function getPaginated(Request $request, Response $response) {
        $query_params = $request->getQueryParams();
        $limit = 15;
        $page = isset($query_params["page"]) ? (int) $query_params["page"] : 1;
        $sort_field = isset($query_params["sort_field"]) ? $query_params["sort_field"] : null;
        $sort_reverse = isset($query_params["sort_reverse"]) ? filter_var($query_params["sort_reverse"], FILTER_VALIDATE_BOOLEAN) : null;
        $search_string = isset($query_params["search_string"]) ? $query_params["search_string"] : null;
        $pagination = $this->usuarios_repository->getPaginationLinks($page, $limit, $search_string);
        $usuarios = $this->usuarios_repository->findAllPaginated($page, $limit, $search_string, $sort_field, $sort_reverse);

        return $response
            ->withHeader("Content-Type", "application/json")
            ->withJson([
                "status" => "ok",
                "data" => $usuarios,
                "links" => $pagination
            ]);
    }

    public function getUsuario(Request $request, Response $response, $arguments) {
        $id = $arguments["id"];
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
