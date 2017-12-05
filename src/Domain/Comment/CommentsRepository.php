<?php
namespace Domain\Comment;

class CommentsRepository
{
    private $comment_model;

    public function __construct() {
        $this->comment_model = new Comment;
    }

    public function getCommentsFromPostIdPaginated($post_id, $options) {
        return $this->comment_model
            ->where('post_id', $post_id)
            ->orderBy(Comment::FIXED_ORDER, $options['order'])
            ->offset($options['offset'])
            ->limit($options['limit'])
            ->get();
    }
}