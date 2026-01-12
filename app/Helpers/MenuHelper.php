<?php

use Illuminate\Support\Str;

if (!function_exists('isActive')) {
    function isActive(string $slug): bool
    {
        return request()->is($slug) || request()->is($slug . '/*');
    }
}

if (!function_exists('isOpen')) {
    function isOpen($children, bool $collapse = false): string
    {
        foreach ($children as $child) {
            if (isActive($child->slug)) {
                return $collapse ? 'show' : 'active';
            }
        }
        return '';
    }
}
