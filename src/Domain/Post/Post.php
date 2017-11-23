<?php
namespace Domain\Post;

use Illuminate\Database\Eloquent\Model as Model;

class Post extends Model
{
    const CREATED_AT = 'creation_date';
    const UPDATED_AT = 'modification_date';

    protected $casts = [
        'metadata' => 'array',
    ];

//    protected $dispatchesEvents = [
//        'created' => PostCreated::class,
//        'deleted' => PostDeleted::class,
//    ];
}