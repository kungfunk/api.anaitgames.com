<?php
namespace Domain\Post;

class CategoriesRepository
{
    private $categories_model;

    public function __construct() {
        $this->categories_model = new Category;
    }

    public function getAll() {
        return $this->categories_model->all();
    }
}