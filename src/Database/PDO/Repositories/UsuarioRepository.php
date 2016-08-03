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
        "rol" => "rol_id"
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
        $data = $this->executor->query(
            "SELECT * FROM usuario WHERE id = :id",
            [
                ":id"  => $id
            ]
        );

        return UsuarioFactory::createUsuarioFromData($data[0]);
    }

    public function getByUsername($username) {
        $data = $this->executor->query(
            "SELECT * FROM usuario WHERE usuario_url = :username",
            [
                ":username" => $username
            ]
        );

        return UsuarioFactory::createUsuarioFromData($data[0]);
    }

    public function findAllPaginated($page, $limit, $sort_field = null, $sort_direction = null) {
        $bounds = PaginationHelper::getRegistryStartAndEnd($page, $limit);
        $query_sort_field = array_key_exists($sort_field, $this->sortable_fields) ? $this->sortable_fields[$sort_field] : $this->sortable_fields["username"];
        $query_sort_direction = in_array($sort_direction, $this->sortable_directions) ? $sort_direction : $this->sortable_directions[0];
        $sql = sprintf("SELECT * FROM usuario ORDER BY %s %s LIMIT %d, %d", $query_sort_field, $query_sort_direction, $bounds["start"], $bounds["end"]);
        $data = $this->executor->query($sql, null);

        $usuarios = [];
        foreach ($data as $usuario) {
            $usuarios[] = UsuarioFactory::createUsuarioFromData($usuario);
        }
        return $usuarios;
    }
    
    public function getPaginationLinks($page, $limit) {
        $total = $this->executor->count("SELECT COUNT(*) FROM usuario");
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

        return $this->executor->exec(
            "INSERT INTO usuarios ($field_list) VALUES ($field_list_colons) ON DUPLICATE KEY UPDATE $field_list_update",
            $dbo
        );
        
    }

    public function delete(Usuario $usuario) {
        return $this->executor->exec(
            "DELETE FROM usuarios WHERE id = :id",
            [
                ":id" => $usuario->id
            ]
        );
    }
}
