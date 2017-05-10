<?php
namespace Libs;

class PaginationHelper
{
    public static function getPaginationLinks($page, $total, $limit) {
        $total_pages = ceil($total/$limit);
        $first = 1;
        $last = $total_pages;
        $prev = $page > 1 ? $page - 1 : null;
        $next = $page < $total_pages ? $page + 1 : null;

        return [
            "first" => $first,
            "last" => $last,
            "next" => $next,
            "prev" => $prev
        ];
    }

    public static function getRegistryOffset($page, $limit) {
        return $page === 1 ? 0 : ($page - 1) * $limit;
    }
}