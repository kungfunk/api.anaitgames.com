<?php
namespace API\GetCommentsFromPost;

use Psr\Http\Message\ServerRequestInterface as Request;
use Exceptions\BadInputException as BadInputException;
use Domain\Post\Post as Post;
use Domain\Comment\Comment as Comment;

class GetCommentsFromPostInput
{
    const MINIMUM_ID = 1;

    const PARAM_LIMIT = 'limit';
    const PARAM_OFFSET = 'offset';
    const PARAM_ORDER = 'order';

    const ORDER_WHITELIST = [
        'desc',
        'asc'
    ];

    const DEFAULT_ORDER = 'desc';
    const DEFAULT_LIMIT = Comment::DEFAULT_LIMIT;
    const DEFAULT_OFFSET = 0;

    public $id;
    public $order;
    public $limit;
    public $offset;

    public function __construct(Request $request) {
        $this->id = (int) $request->getAttribute(Post::ID);
        $this->offset = $request->getQueryParam($this::PARAM_OFFSET, $default = $this::DEFAULT_OFFSET);
        $this->limit = $request->getQueryParam($this::PARAM_LIMIT, $default = $this::DEFAULT_LIMIT);
        $this->order = $request->getQueryParam($this::PARAM_ORDER, $default = $this::DEFAULT_ORDER);

        if($this->id < $this::MINIMUM_ID) {
            throw new BadInputException(BadInputException::IDENTIFIER_MALFORMED);
        }

        if(
            $this->offset < $this::DEFAULT_OFFSET ||
            !in_array($this->order, $this::ORDER_WHITELIST)
        ) {
            throw new BadInputException(BadInputException::BAD_QUERY_VALUE);
        }
    }
}