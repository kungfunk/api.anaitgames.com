<?php
namespace Action\Posts;

use Psr\Http\Message\ServerRequestInterface as Request;
use Exceptions\BadInputException as BadInputException;

class GetPostsInput
{
    const PARAM_SEARCH = 'search';
    const PARAM_TYPE = 'type';
    const PARAM_STATUS = 'status';
    const PARAM_TAGS = 'tags';
    const PARAM_ORDER_BY = 'order_by';
    const PARAM_ORDER = 'order';

    const STATUS_WHITELIST = [
        'published',
        'thrash',
        'draft'
    ];

    const ORDER_WHITELIST = [
        'desc',
        'asc'
    ];

    const ORDER_BY_WHITELIST = [
        'creation_date',
        'publish_date',
        'title',
        'num_views'
    ];

    const DEFAULT_STATUS = 'published';
    const TAG_DELIMITER = '|';

    public $search;
    public $type;
    public $status;
    public $tags;
    public $order_by;
    public $order;

    public function __construct(Request $request) {
        try {
            $this->search = $request->getQueryParam($this::PARAM_SEARCH, $default = null);
            $this->type = $request->getQueryParam($this::PARAM_TYPE, $default = null);

            $_status = $request->getQueryParam($this::PARAM_SEARCH, $default = $this::DEFAULT_STATUS);
            $this->status = in_array($_status, $this::ORDER_BY_WHITELIST) ? $_status : null;

            $_tags = $request->getQueryParam($this::PARAM_TAGS, $default = null);
            $this->tags = !is_null($_tags) ? explode($this::TAG_DELIMITER, $_tags) : [];

            $_order_by = $request->getQueryParam($this::PARAM_ORDER_BY, $default = null);
            $this->order_by = in_array($_order_by, $this::ORDER_BY_WHITELIST) ? $_order_by : null;

            $_order = $request->getQueryParam($this::PARAM_ORDER, $default = null);
            $this->order = in_array($_order, $this::ORDER_WHITELIST) ? $_order : null;
        }
        catch (\Exception $error) {
            throw new BadInputException($error);
        }
    }
}