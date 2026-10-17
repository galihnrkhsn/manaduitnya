<?php

use Carbon\Carbon;

if (!function_exists('parseMonth')) {
    function parseMonth(?string $value): Carbon {
        try {
            return Carbon::createFromFormat('!Y-m', $value ?? now()->format('Y-m'));
        } catch (\Throwable) {
            return now()->startOfMonth();
        }
    }
}
