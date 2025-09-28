<?php

use Illuminate\Pagination\LengthAwarePaginator;

function obfuscate_email(?string $email = null): string
{
    if (! $email) {
        return '';
    }

    $split = explode('@', $email);

    if (count($split) != 2) {
        return '';
    }

    $firstPart       = $split[0];
    $qty             = (int) floor(strlen($firstPart) * 0.75);
    $remaining       = strlen($firstPart) - $qty;
    $maskedFirstPart = substr($firstPart, 0, $remaining) . str_repeat('*', $qty);

    $secondPart       = $split[1];
    $qty              = (int) floor(strlen($secondPart) * 0.75);
    $remaining        = strlen($secondPart) - $qty;
    $maskedSecondPart = str_repeat('*', $qty) . substr($secondPart, $remaining * -1, $remaining);

    return $maskedFirstPart . '@' . $maskedSecondPart;
}

/**
 * Get subdomain from the URL. !! requires passing the request host !!
 */
function get_subdomain(?string $host = null): ?string
{
    if (is_null($host)) {
        $host = request()->getHost();
    }

    $parts = explode('.', $host);

    return count($parts) > 2 ? $parts[0] : null;
}

/**
 * Mount the route with subdomain
 */
function get_subdomain_route(string $route, string $subdomain): string
{
    $parts_route          = parse_url($route);
    $subdomain            = ! str_ends_with($subdomain, '.') ? $subdomain . '.' : $subdomain;
    $route_with_subdomain = $parts_route['scheme'] . '://' . $subdomain . $parts_route['host'] . (isset($parts_route['port']) ? ':' . $parts_route['port'] : '') . $parts_route['path'];

    return $route_with_subdomain;
}

/**
 * Returns only numerics from a string
 */
function only_numerics(null|int|string $value): string
{
    return preg_replace("/\D/", '', $value);
}

/**
 * Sorts a paginated collection.
 */
function sort_paginated_collection(LengthAwarePaginator $paginated_data, callable $callback, string $direction = 'asc'): LengthAwarePaginator
{
    // Sort a collection using the callback
    $sorted = $paginated_data->getCollection()->sortBy($callback);

    if (strtolower($direction) === 'desc') {
        $sorted = $sorted->reverse();
    }

    // Creates a new paginator keeping the original pagination
    return new LengthAwarePaginator(
        $sorted->values(),
        $paginated_data->total(),
        $paginated_data->perPage(),
        $paginated_data->currentPage(),
        [
            'path'  => LengthAwarePaginator::resolveCurrentPath(),
            'query' => request()->query(),
        ]
    );
}
