<?php

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
