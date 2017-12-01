<?php
namespace API\GetCommentsFromPost;

use Psr\Http\Message\ServerRequestInterface as Request;
use Exceptions\BadInputException as BadInputException;
use Domain\Post\Post as Post;

class GetCommentsFromPostInput
{
    const MINIMUM_ID = 1;

    public $id;

    public function __construct(Request $request) {
        $this->id = (int) $request->getAttribute(Post::ID);

        if($this->id < $this::MINIMUM_ID) {
            throw new BadInputException(BadInputException::IDENTIFIER_MALFORMED);
        }
    }
}