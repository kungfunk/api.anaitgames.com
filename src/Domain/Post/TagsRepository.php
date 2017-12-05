<?php
namespace Domain\Post;

class TagsRepository
{
    private $tags_model;

    public function __construct() {
        $this->tags_model = new Tag;
    }

    public function getAll() {
        return $this->tags_model->all();
    }
}