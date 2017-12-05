<?php
namespace API\GetUsers;

use API\Input;
use Psr\Http\Message\ServerRequestInterface as Request;
use Exceptions\BadInputException as BadInputException;
use Domain\User\User as User;
use Libs\SlugValidator as SlugValidator;

class GetUsersInput extends Input
{
    const PARAM_USERNAME = 'username';

    const ORDER_BY_WHITELIST = [
        User::ORDER_BY_REGISTER_DATE,
        User::ORDER_BY_FULL_NAME,
        User::ORDER_BY_ROLE
    ];

    const DEFAULT_LIMIT = User::DEFAULT_LIMIT;
    const DEFAULT_ORDER_BY = User::ORDER_BY_REGISTER_DATE;

    const MAX_LIMIT = 50;

    public $search;
    public $username;
    public $order_by;
    public $order;
    public $limit;
    public $offset;

    public function __construct(Request $request) {
        $this->search = $request->getQueryParam($this::PARAM_SEARCH, $default = null);
        $this->username = $request->getQueryParam($this::PARAM_USERNAME, $default = null);
        $this->order_by = $request->getQueryParam($this::PARAM_ORDER_BY, $default = $this::DEFAULT_ORDER_BY);
        $this->order = $request->getQueryParam($this::PARAM_ORDER, $default = $this::DEFAULT_ORDER);
        $this->offset = $request->getQueryParam($this::PARAM_OFFSET, $default = $this::DEFAULT_OFFSET);
        $this->limit = $request->getQueryParam($this::PARAM_LIMIT, $default = $this::DEFAULT_LIMIT);

        $this->isValidLimit($this->limit);
        $this->isValidOffset($this->offset);
        $this->isValidOrder($this->order, $this->order_by);

        if(!is_null($this->username)) {
            $this->isValidUsername($this->username);
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

    private function isValidOrder($order, $order_by) {
        if(
            !in_array($order_by, $this::ORDER_BY_WHITELIST) ||
            !in_array($order, $this::ORDER_WHITELIST)
        ) {
            throw new BadInputException(BadInputException::BAD_QUERY_VALUE);
        }
    }

    private function isValidUsername($username) {
        if(!SlugValidator::checkSlug($username, true)) {
            throw new BadInputException(BadInputException::BAD_QUERY_VALUE);
        }
    }
}