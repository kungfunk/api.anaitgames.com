<?php
namespace Api\Controller;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \Slim\Container as ContainerInterface;
use \Database\PDO\Repositories\ArticuloRepository as ArticuloRepository;

class Articulos
{
    protected $ci;
    private $articulos_repository;

    public function __construct(ContainerInterface $ci) {
        $this->ci = $ci;
        $this->articulos_repository = new ArticuloRepository;
    }

    public function getArticulosPaginated(Request $request, Response $response) {
        $query_params = $request->getQueryParams();
        $limit = 15;
        $page = isset($query_params["page"]) ? (int) $query_params["page"] : 1;
        $sort_field = isset($query_params["sort_field"]) ? $query_params["sort_field"] : null;
        $sort_reverse = isset($query_params["sort_reverse"]) ? filter_var($query_params["sort_reverse"], FILTER_VALIDATE_BOOLEAN) : null;
        $search_string = isset($query_params["search_string"]) ? $query_params["search_string"] : null;
        $pagination = $this->articulos_repository->getPaginationLinks($page, $limit, $search_string);
        $articulos = $this->articulos_repository->findAllPaginated($page, $limit, $search_string, $sort_field, $sort_reverse);

        return $response
            ->withHeader("Content-Type", "application/json")
            ->withJson([
                "status" => "ok",
                "data" => $articulos,
                "links" => $pagination
            ]);
    }

    public function getArticulo(Request $request, Response $response, $arguments) {
        $id = $arguments["id"];
        $articulo = $this->articulos_repository->getById($id);

        return $response
            ->withHeader("Content-Type", "application/json")
            ->withJson([
                "status" => "ok",
                "data" => $articulo
            ]);
    }

    public function saveArticulo(Request $request, Response $response, $arguments) {

    }
}
