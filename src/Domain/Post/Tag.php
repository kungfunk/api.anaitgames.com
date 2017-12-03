<?php
namespace Domain\Post;

use Illuminate\Database\Eloquent\Model as Model;

class Tag extends Model
{
    const TABLE_NAME = 'posts_tags';
}