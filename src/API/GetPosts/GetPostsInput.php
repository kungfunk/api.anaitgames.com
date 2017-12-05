<?php
namespace API\GetPosts;

use API\Input;
use Psr\Http\Message\ServerRequestInterface as Request;
use Exceptions\BadInputException as BadInputException;
use Domain\Post\Post as Post;
use Libs\SlugValidator as SlugValidator;

class GetPostsInput extends Input
{
    const PARAM_TYPE = 'type';
    const PARAM_STATUS = 'status';
    const PARAM_TAGS = 'tags';
    const PARAM_SLUG = 'slug';

    const STATUS_WHITELIST = [
        Post::STATUS_DRAFT,
        Post::STATUS_PUBLISHED,
        Post::STATUS_TRASH
    ];

    const ORDER_BY_WHITELIST = [
        Post::ORDER_BY_CREATION_DATE,
        Post::ORDER_BY_PUBLISH_DATE,
        Post::ORDER_BY_TITLE,
        Post::ORDER_BY_NUM_VIEWS
    ];

    const DEFAULT_STATUS = Post::STATUS_PUBLISHED;
    const DEFAULT_LIMIT = Post::DEFAULT_LIMIT;
    const DEFAULT_ORDER_BY = Post::ORDER_BY_PUBLISH_DATE;

    const MAX_LIMIT = 50;
    const TAG_DELIMITER = '|';

    public $search;
    public $type;
    public $slug;
    public $status;
    public $tags;
    public $order_by;
    public $order;
    public $limit;
    public $offset;

    public function __construct(Request $request) {
        $this->search = $request->getQueryParam($this::PARAM_SEARCH, $default = null);
        $this->type = $request->getQueryParam($this::PARAM_TYPE, $default = null);
        $this->slug = $request->getQueryParam($this::PARAM_SLUG, $default = null);
        $this->status = $request->getQueryParam($this::PARAM_STATUS, $default = $this::DEFAULT_STATUS);
        $this->order_by = $request->getQueryParam($this::PARAM_ORDER_BY, $default = $this::DEFAULT_ORDER_BY);
        $this->order = $request->getQueryParam($this::PARAM_ORDER, $default = $this::DEFAULT_ORDER);
        $this->offset = $request->getQueryParam($this::PARAM_OFFSET, $default = $this::DEFAULT_OFFSET);
        $this->limit = $request->getQueryParam($this::PARAM_LIMIT, $default = $this::DEFAULT_LIMIT);

        $_tags = $request->getQueryParam($this::PARAM_TAGS, $default = null);
        $this->tags = !is_null($_tags) ? explode($this::TAG_DELIMITER, $_tags) : [];

        $this->isValidLimit($this->limit);
        $this->isValidOffset($this->offset);
        $this->isValidStatus($this->status);
        $this->isValidOrder($this->order, $this->order_by);

        if(!is_null($this->slug)) {
            $this->isValidSlug($this->slug);
        }
    }

    private function isValidLimit($limit) {
        if($limit >= $this::MAX_LIMIT) {
            throw new BadInputException(BadInputException::LIMIT_EXCEEDED);
        }
    }

    private function isValidOffset($offset) {
        if($offset < $this::DEFAULT_OFFSET) {
            throw new BadInputException(BadInputException::BAD_QUERY_VALUE);
        }
    }

    private function isValidStatus($status) {
        if(!in_array($status, $this::STATUS_WHITELIST)) {
            throw new BadInputException(BadInputException::BAD_QUERY_VALUE);
        }
    }

    private function isValidOrder($order, $order_by) {
        if(
            !in_array($order_by, $this::ORDER_BY_WHITELIST) ||
            !in_array($order, $this::ORDER_WHITELIST)
        ) {
            throw new BadInputException(BadInputException::BAD_QUERY_VALUE);
        }
    }

    private function isValidSlug($slug) {
        if(!SlugValidator::checkSlug($slug)) {
            throw new BadInputException(BadInputException::BAD_QUERY_VALUE);
        }
    }
}