<?php
namespace Domain\Post;

use Illuminate\Database\Eloquent\Model as Model;

class Post extends Model
{
    const CREATED_AT = 'creation_date';
    const UPDATED_AT = 'modification_date';

    const STATUS_DRAFT = 'draft';
    const STATUS_PUBLISHED = 'published';
    const STATUS_TRASH = 'trash';

    const ORDER_BY_CREATION_DATE = 'creation_date';
    const ORDER_BY_PUBLISH_DATE = 'publish_date';
    const ORDER_BY_TITLE = 'title';
    const ORDER_BY_NUM_VIEWS = 'num_views';

    const DEFAULT_LIMIT = 10;

    protected $casts = [
        'metadata' => 'array',
    ];

//    protected $dispatchesEvents = [
//        'created' => PostCreated::class,
//        'deleted' => PostDeleted::class,
//    ];
}