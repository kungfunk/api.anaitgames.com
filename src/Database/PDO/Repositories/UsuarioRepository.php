<?php
namespace Database\PDO\Repositories;

use \Domain\Usuario\Usuario;
use \Domain\Usuario\UsuarioRepositoryInterface as UsuarioRepositoryInterface;
use \Domain\Usuario\UsuarioFactory as UsuarioFactory;
use \Libs\PaginationHelper as PaginationHelper;
use \Database\PDO\Connector as Connector;
use \Database\PDO\Executor as Executor;

class UsuarioRepository implements UsuarioRepositoryInterface
{
    protected $connector;
    protected $executor;

    private static $db_querys = [
        "get_by_id" => "SELECT * FROM usuario WHERE id = :id",
        "get_by_username" => "SELECT * FROM usuario WHERE usuario_url = :username",
        "find_all_limit_offset" => "SELECT * FROM usuario ORDER BY %s %s LIMIT %d OFFSET %d",
        "find_all_search_limit_offset" => "SELECT * FROM usuario WHERE usuario_url like :search OR usuario like :search OR email like :search ORDER BY %s %s LIMIT %d OFFSET %d",
        "count_all" => "SELECT COUNT(*) FROM usuario",
        "count_all_search" => "SELECT COUNT(*) FROM usuario WHERE usuario_url like :search OR usuario like :search OR email like :search",
        "insert_or_update" => "INSERT INTO usuarios (%s) VALUES (%s) ON DUPLICATE KEY UPDATE %s",
        "delete" => "DELETE FROM usuarios WHERE id = :id"
    ];

    private static $sortable_fields = [
        "username" => "usuario_url",
        "fecha_alta" => "fecha_alta",
        "nombre" => "usuario",
        "email" => "email",
        "rol" => "id_rol"
    ];

    private static $sortable_directions = [
        "asc",
        "desc"
    ];

    public function __construct() {
        $this->connector = Connector::getInstance();
        $this->executor = new Executor($this->connector);
    }

    public function getById($id) {
        $this->executor->prepare(
            self::$db_querys["get_by_id"],
            [
                ":id"  => $id
            ]
        );

        $data = $this->executor->fetch();
        return UsuarioFactory::createUsuarioFromData($data);
    }

    public function getByUsername($username) {
        $this->executor->prepare(
            self::$db_querys["get_by_username"],
            [
                ":username" => $username
            ]
        );

        $data = $this->executor->fetch();
        return UsuarioFactory::createUsuarioFromData($data);
    }

    public function findAllPaginated($page, $limit, $search_string = null, $sort_field = null, $sort_reverse = null) {
        $offset = PaginationHelper::getRegistryOffset($page, $limit);
        $query_sort_field = array_key_exists($sort_field, self::$sortable_fields) ? self::$sortable_fields[$sort_field] : self::$sortable_fields["username"];
        $query_sort_direction = $sort_reverse === true ? self::$sortable_directions[1] : self::$sortable_directions[0];
        $params = null;
        $query = self::$db_querys["find_all_limit_offset"];
        if(!empty($search_string)) {
            $query = self::$db_querys["find_all_search_limit_offset"];
            $params = [
                ":search" => "%".$search_string."%"
            ];
        }
        $sql = sprintf($query, $query_sort_field, $query_sort_direction, $limit, $offset);
        $this->executor->prepare($sql, $params);
        $data = $this->executor->fetchAll();

        $usuarios = [];
        foreach ($data as $usuario) {
            $usuarios[] = UsuarioFactory::createUsuarioFromData($usuario);
        }
        return $usuarios;
    }
    
    public function getPaginationLinks($page, $limit, $search_string = null) {
        $params = null;
        $sql = self::$db_querys["count_all"];
        if(!empty($search_string)) {
            $sql = self::$db_querys["count_all_search"];
            $params = [
                ":search" => "%".$search_string."%"
            ];
        }
        $this->executor->prepare($sql, $params);
        $total = $this->executor->count();
        return PaginationHelper::getPaginationLinks($page, $total, $limit);
    }

    public function save(Usuario $usuario) {
        $dbo = UsuarioFactory::createDBOFromUsuario($usuario);

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

        $sql = sprintf(self::$db_querys["insert_or_update"], $field_list, $field_list_colons, $field_list_update);
        $this->executor->prepare($sql, $dbo);

        return $this->executor->exec();
    }

    public function delete(Usuario $usuario) {
        $this->executor->prepare(
            self::$db_querys["delete"],
            [
                ":id" => $usuario->id
            ]
        );

        return $this->executor->exec();
    }
}
