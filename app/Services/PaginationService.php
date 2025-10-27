<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class PaginationService
{
    /**
     * Paginer une query et générer les liens.
     *
     * @param Builder|Collection $query
     * @param int $page
     * @param int $limit
     * @param string $baseUrl
     * @return array
     */
    public static function paginate($query, int $page = 1, int $limit = 10, string $baseUrl = ''): array
    {
        // Si c'est une collection Eloquent, convertir en Builder pour count()
        if ($query instanceof Collection) {
            $totalItems = $query->count();
            $items = $query->slice(($page - 1) * $limit, $limit)->values();
        } else {
            $totalItems = $query->count();
            $items = $query->skip(($page - 1) * $limit)->take($limit)->get();
        }

        $totalPages = ceil($totalItems / $limit);

        $pagination = [
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalItems' => $totalItems,
            'itemsPerPage' => $limit,
            'hasNext' => $page < $totalPages,
            'hasPrevious' => $page > 1,
        ];

        $links = [
            'self' => $baseUrl . "?page={$page}&limit={$limit}",
            'next' => $pagination['hasNext'] ? $baseUrl . "?page=" . ($page + 1) . "&limit={$limit}" : null,
            'first' => $baseUrl . "?page=1&limit={$limit}",
            'last' => $baseUrl . "?page={$totalPages}&limit={$limit}",
        ];

        return [
            'items' => $items,
            'pagination' => $pagination,
            'links' => $links,
        ];
    }
}
