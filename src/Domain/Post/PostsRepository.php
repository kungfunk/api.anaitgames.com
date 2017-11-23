<?php

namespace Domain\Post;

class PostsRepository
{
    const DEFAULT_ORDER = 'desc';
    const OPERATOR_LIKE = 'like';
    const LIKE_BOUNDERS = '%';

    private $post_model;

    function __construct() {
        $this->post_model = new Post;
    }

    function getPostsPaginated($options, $limit = 10) {
        $query = $this->post_model
            ->query()
            ->select('name', 'email as user_email');

        if(!is_null($options['search'])) {
            $query = $query->where(
                'title',
                $this::OPERATOR_LIKE,
                $this::LIKE_BOUNDERS . $options['search'] . $this::LIKE_BOUNDERS
            );
        }

        if(!is_null($options['order_by'])) {
            $order = !is_null($options['order']) ? $options['order'] : $this::DEFAULT_ORDER;
            $query = $query->orderBy($options['order_by'], $order);
        }

        $query = $query->take($limit);
        return $query->get();
    }
}