<?php
namespace Database\PDO\Repositories;

use \Domain\Usuario\Usuario;
use \Domain\Usuario\UsuarioRepositoryInterface as UsuarioRepositoryInterface;
use \Domain\Usuario\UsuarioFactory as UsuarioFactory;
use \Database\PDO\Connector as Connector;
use \Database\PDO\Executor as Executor;
use \PDO;

class UsuarioRepository implements UsuarioRepositoryInterface
{
    protected $connector;
    protected $executor;

    public function __construct() {
        $this->connector = Connector::getInstance();
        $this->executor = new Executor($this->connector);
    }

    public function getById($id) {
        $data = $this->executor->query(
            "SELECT * FROM usuarios WHERE id = :id",
            [
                ":id"  => $id
            ]
        );

        return UsuarioFactory::createUsuarioFromData($data[0]);
    }

    public function getByUsername($username) {
        $data = $this->executor->query(
            "SELECT * FROM usuarios WHERE usuario_url = :username'",
            [
                ":username" => $username
            ]
        );

        return UsuarioFactory::createUsuarioFromData($data[0]);
    }

    public function findAllPaginated($pag, $limit) {

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
