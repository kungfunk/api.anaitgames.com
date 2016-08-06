<?php
namespace Database\PDO\Repositories;

use \Domain\Articulo\Articulo;
use \Domain\Articulo\ArticuloRepositoryInterface as ArticuloRepositoryInterface;
use \Domain\Articulo\ArticuloFactory as ArticuloFactory;
use \Libs\PaginationHelper as PaginationHelper;
use \Database\PDO\Connector as Connector;
use \Database\PDO\Executor as Executor;

class ArticuloRepository implements ArticuloRepositoryInterface
{
    protected $connector;
    protected $executor;

    //TODO: need to add all the related tables to the querys (join)
    private $db_querys = [
        "get_by_id" => "SELECT * FROM articulo WHERE id = :id",
        "get_by_url" => "SELECT * FROM articulo WHERE url = :url",
        "find_all_limit_offset" => "SELECT * FROM articulo ORDER BY %s %s LIMIT %d OFFSET %d",
        "find_all_search_limit_offset" => "SELECT * FROM articulo WHERE titular like :search OR tipo like :search ORDER BY %s %s LIMIT %d OFFSET %d",
        "count_all" => "SELECT COUNT(*) FROM articulo",
        "count_all_search" => "SELECT COUNT(*) FROM articulo WHERE titular like :search OR tipo like :search",
        "insert_or_update" => "INSERT INTO articulo (%s) VALUES (%s) ON DUPLICATE KEY UPDATE %s",
        "delete" => "DELETE FROM articulo WHERE id = :id"
    ];

    private $sortable_fields = [
        "titular" => "titular",
        "tipo" => "tipo",
        "fecha_publicacion" => "fecha_publicacion",
        "estado" => "estado",
        "visitas" => "numero_visitas",
        "numero_comentarios" => "numero_comentarios"
    ];

    private $sortable_directions = [
        "asc",
        "desc"
    ];

    public function __construct() {
        $this->connector = Connector::getInstance();
        $this->executor = new Executor($this->connector);
    }

    public function getById($id) {
        $this->executor->prepare(
            $this->db_querys["get_by_id"],
            [
                ":id"  => $id
            ]
        );

        $data = $this->executor->fetch();
        return ArticuloFactory::createUsuarioFromData($data);
    }

    public function getByUrl($url) {
        $this->executor->prepare(
            $this->db_querys["get_by_url"],
            [
                ":url" => $url
            ]
        );

        $data = $this->executor->fetch();
        return ArticuloFactory::createArticuloFromData($data);
    }

    public function findAllPaginated($page, $limit, $search_string = null, $sort_field = null, $sort_reverse = null) {
        $offset = PaginationHelper::getRegistryOffset($page, $limit);
        $query_sort_field = array_key_exists($sort_field, $this->sortable_fields) ? $this->sortable_fields[$sort_field] : $this->sortable_fields["username"];
        $query_sort_direction = $sort_reverse === true ? $this->sortable_directions[1] : $this->sortable_directions[0];
        $params = null;
        $query = $this->db_querys["find_all_limit_offset"];
        if(!empty($search_string)) {
            $query = $this->db_querys["find_all_search_limit_offset"];
            $params = [
                ":search" => "%".$search_string."%"
            ];
        }
        $sql = sprintf($query, $query_sort_field, $query_sort_direction, $limit, $offset);
        $this->executor->prepare($sql, $params);
        $data = $this->executor->fetchAll();

        $usuarios = [];
        foreach ($data as $usuario) {
            $usuarios[] = ArticuloFactory::createArticuloFromData($usuario);
        }
        return $usuarios;
    }

    public function getPaginationLinks($page, $limit, $search_string = null) {
        $params = null;
        $sql = $this->db_querys["count_all"];
        if(!empty($search_string)) {
            $sql = $this->db_querys["count_all_search"];
            $params = [
                ":search" => "%".$search_string."%"
            ];
        }
        $this->executor->prepare($sql, $params);
        $total = $this->executor->count();
        return PaginationHelper::getPaginationLinks($page, $total, $limit);
    }

    public function save(Articulo $usuario) {
        $dbo = ArticuloFactory::createDBOFromArticulo($usuario);

        $fields = array_keys($dbo);
        $fields_colons = [];
        $fields_update = [];
        foreach ($fields as $field) {
            $fields_colons[] = ":$field";
            $fields_update[] = "$field=:$field";
        }

        $field_list = implode(", ", $fields);
        $field_list_colons = implode(", ", $fields_colons);
        $field_list_update = implode(", ", $fields_update);

        $sql = sprintf($this->db_querys["insert_or_update"], $field_list, $field_list_colons, $field_list_update);
        $this->executor->prepare($sql, $dbo);

        return $this->executor->exec();
    }

    public function delete(Articulo $usuario) {
        $this->executor->prepare(
            $this->db_querys["delete"],
            [
                ":id" => $usuario->id
            ]
        );

        return $this->executor->exec();
    }
}
