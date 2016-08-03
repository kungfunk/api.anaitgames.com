<?php
namespace Domain\Usuario;

interface UsuarioRepositoryInterface {
    public function save(Usuario $usuario);
    public function delete(Usuario $usuario);
    public function findAllPaginated($pag, $limit, $sort_field, $sort_direction);
    public function getById($id);
    public function getByUsername($name);
    public function getPaginationLinks($pag, $limit);
}
