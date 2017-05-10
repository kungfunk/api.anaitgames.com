<?php
namespace Domain\Articulo;

interface ArticuloRepositoryInterface {
    public function save(Articulo $articulo);
    public function delete(Articulo $articulo);
    public function findAllPaginated($page, $limit, $search_string, $sort_field, $sort_direction);
    public function getById($id);
    public function getByUrl($url);
    public function getPaginationLinks($pag, $limit);
}
