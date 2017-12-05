<?php
namespace Domain\User;

class UsersRepository
{
    const OPERATOR_LIKE = 'like';
    const LIKE_BOUNDERS = '%';

    private $user_model;

    public function __construct() {
        $this->user_model = new User;
    }

    public function getUserById($id) {
        return $this->user_model->find($id);
    }

    public function getUsersPaginated($options) {
        // TODO: add type and tags to the filters
        $query = $this->user_model->query();

        if(!is_null($options['username'])) {
            $query = $query->where('username', $options['username']);
        }

        if(!is_null($options['search'])) {
            $query = $query->where(
                User::SEARCHABLE_FIELD,
                $this::OPERATOR_LIKE,
                $this::LIKE_BOUNDERS . $options['search'] . $this::LIKE_BOUNDERS
            );
        }

        return $query
            ->orderBy($options['order_by'], $options['order'])
            ->offset($options['offset'])
            ->limit($options['limit'])
            ->get();
    }
}