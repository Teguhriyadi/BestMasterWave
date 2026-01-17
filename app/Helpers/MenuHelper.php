<?php

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
            if (isActive('admin-panel/' . $child->url_menu)) {
                return $collapse ? 'show' : 'active';
            }
        }
        return '';
    }
}


if (!function_exists('menuReadPermission')) {
    function menuReadPermission($menu): ?string
    {
        if (!$menu->url_menu) {
            return null;
        }

        // contoh: jabatan → jabatan.read
        return $menu->url_menu . '.read';
    }

}
