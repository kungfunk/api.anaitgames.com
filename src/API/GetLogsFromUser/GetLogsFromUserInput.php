<?php
namespace API\GetLogsFromUser;

use API\Input;
use Psr\Http\Message\ServerRequestInterface as Request;
use Exceptions\BadInputException as BadInputException;
use Domain\User\User as User;

class GetLogsFromUserInput extends Input
{
    public $id;
    public $order;
    public $limit;
    public $offset;

    public function __construct(Request $request) {
        $this->id = (int) $request->getAttribute(User::ID);
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