<?php
namespace API\GetPostById;

use Psr\Http\Message\ServerRequestInterface as Request;
use Exceptions\BadInputException as BadInputException;
use Domain\Post\Post as Post;

class GetPostBySlugInput
{
    const MINIMUM_ID = 1;

    public $slug;

    public function __construct(Request $request) {
        $this->slug = $request->getAttribute(Post::SLUG);

        //TODO: check correct formed slug '/^[a-z][-a-z0-9]*$/'
        if($this->slug) {
            throw new BadInputException(BadInputException::IDENTIFIER_MALFORMED);
        }
    }
}