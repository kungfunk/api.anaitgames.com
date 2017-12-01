<?php

namespace Domain\Post;

class PostsRepository
{
    const OPERATOR_LIKE = 'like';
    const LIKE_BOUNDERS = '%';

    private $post_model;

    public function __construct() {
        $this->post_model = new Post;
    }

    public function getPostById($id) {
        return $this->post_model->find($id);
    }

    public function getPostBySlug($slug) {
        return $this->post_model->where(Post::SLUG, $slug)->first();
    }

    public function getPostsPaginated($options) {
        // TODO: add type and tags to the filters
        $query = $this->post_model->query();

        if(!is_null($options['search'])) {
            $query = $query->where(
                'title',
                $this::OPERATOR_LIKE,
                $this::LIKE_BOUNDERS . $options['search'] . $this::LIKE_BOUNDERS
            );
        }

        return $query
            ->where('status', $options['status'])
            ->orderBy($options['order_by'], $options['order'])
            ->offset($options['offset'])
            ->limit($options['limit'])
            ->get();
    }
}