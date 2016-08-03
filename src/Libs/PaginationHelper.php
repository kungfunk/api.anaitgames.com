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

    public static function getRegistryStartAndEnd($page, $limit) {
        //TODO: change to scalar type hints
        $start = (int) $page === 1 ? 0 : (int) $page * (int) $limit;
        $end = (int) $start + (int) $limit;

        return [
            "start" => $start,
            "end" => $end
        ];
    }
}