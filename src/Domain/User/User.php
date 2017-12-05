<?php
namespace Domain\User;

use Illuminate\Database\Eloquent\Model as Model;

class User extends Model
{
    const ID = 'id';

    const ORDER_BY_REGISTER_DATE = 'register_date';
    const ORDER_BY_FULL_NAME = 'full_name';
    const ORDER_BY_ROLE = 'role';

    const DEFAULT_LIMIT = 10;
    const SEARCHABLE_FIELD = 'full_name';

    protected $hidden = ['password'];

    public function posts() {
        return $this->hasMany('Domain\Posts\Posts');
    }

    public function logs() {
        return $this->hasMany('Domain\User\Log');
    }
}