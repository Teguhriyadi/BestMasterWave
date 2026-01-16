<?php

if (! function_exists('normalizeNama')) {
    function normalizeNama($value)
    {
        return strtolower(
            preg_replace('/\s+/', ' ', trim($value))
        );
    }
}
