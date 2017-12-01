<?php
namespace Domain\Comment;

use Illuminate\Database\Eloquent\Model as Model;

class Comment extends Model
{
    const CREATED_AT = 'creation_date';
    const UPDATED_AT = 'modification_date';

    const DEFAULT_LIMIT = 50;
    const FIXED_ORDER = 'creation_date';

    public function post() {
        return $this->belongsTo('Domain\Post\Post');
    }
}