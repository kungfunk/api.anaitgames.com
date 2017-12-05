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

    public function getPostsPaginated($options) {
        // TODO: add type and tags to the filters
        $query = $this->post_model->query();

        if(!is_null($options['search'])) {
            $query = $query->where(
                Post::SEARCHABLE_FIELD,
                $this::OPERATOR_LIKE,
                $this::LIKE_BOUNDERS . $options['search'] . $this::LIKE_BOUNDERS
            );
        }

        if(!is_null($options['slug'])) {
            $query = $query->where(Post::SLUG, $options['slug']);
        }

        return $query
            ->where('status', $options['status'])
            ->withCount('comments')
            ->with('category')
            ->with('user')
            ->with('tags')
            ->orderBy($options['order_by'], $options['order'])
            ->offset($options['offset'])
            ->limit($options['limit'])
            ->get();
    }
}