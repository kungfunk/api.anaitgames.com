<?php
namespace Domain\Post;

use Illuminate\Database\Eloquent\Model as Model;

class Post extends Model
{
    const ID = 'id';
    const SLUG = 'slug';

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
    const SEARCHABLE_FIELD = 'title';

    protected $casts = [
        'metadata' => 'array',
    ];

    public function comments() {
        return $this->hasMany('Domain\Comment\Comment');
    }

    public function user() {
        return $this->belongsTo('Domain\User\User');
    }

    public function category() {
        return $this->belongsTo('Domain\Post\Category');
    }

    public function tags() {
        return $this->belongsToMany('Domain\Post\Tag', Tag::TABLE_NAME);
    }

//    protected $dispatchesEvents = [
//        'created' => PostCreated::class,
//        'deleted' => PostDeleted::class,
//    ];
}