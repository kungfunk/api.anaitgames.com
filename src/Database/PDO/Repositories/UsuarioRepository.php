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

    private $sortable_fields = [
        "username" => "usuario_url",
        "fecha_alta" => "fecha_alta",
        "nombre" => "usuario",
        "email" => "email",
        "rol" => "id_rol"
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
            "SELECT * FROM usuario WHERE id = :id",
            [
                ":id"  => $id
            ]
        );

        $data = $this->executor->fetch();
        return UsuarioFactory::createUsuarioFromData($data);
    }

    public function getByUsername($username) {
        $this->executor->prepare(
            "SELECT * FROM usuario WHERE usuario_url = :username",
            [
                ":username" => $username
            ]
        );

        $data = $this->executor->fetch();
        return UsuarioFactory::createUsuarioFromData($data);
    }

    public function findAllPaginated($page, $limit, $search_string = null, $sort_field = null, $sort_reverse = null) {
        $offset = PaginationHelper::getRegistryOffset($page, $limit);
        $query_sort_field = array_key_exists($sort_field, $this->sortable_fields) ? $this->sortable_fields[$sort_field] : $this->sortable_fields["username"];
        $query_sort_direction = $sort_reverse === true ? $this->sortable_directions[1] : $this->sortable_directions[0];
        $params = null;
        $where = null;
        if(!empty($search_string)) {
            $where = "WHERE usuario_url like :search OR usuario like :search OR email like :search";
            $params = [
                ":search" => "%".$search_string."%"
            ];
        }
        $sql = sprintf("SELECT * FROM usuario %s ORDER BY %s %s LIMIT %d OFFSET %d", $where, $query_sort_field, $query_sort_direction, $limit, $offset);
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
        $where = null;
        if(!empty($search_string)) {
            $where = "WHERE usuario_url like :search OR usuario like :search OR email like :search";
            $params = [
                ":search" => "%".$search_string."%"
            ];
        }
        $sql = sprintf("SELECT COUNT(*) FROM usuario %s", $where);
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

        $this->executor->prepare(
            "INSERT INTO usuarios ($field_list) VALUES ($field_list_colons) ON DUPLICATE KEY UPDATE $field_list_update",
            $dbo
        );

        return $this->executor->exec();
    }

    public function delete(Usuario $usuario) {
        $this->executor->prepare(
            "DELETE FROM usuarios WHERE id = :id",
            [
                ":id" => $usuario->id
            ]
        );

        return $this->executor->exec();
    }
}
