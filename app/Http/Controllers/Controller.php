<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

abstract class Controller
{
    /**
     * Page size for paginated index endpoints: ?per_page=N, default 15, clamped to 1-100.
     */
    protected function perPage(Request $request): int
    {
        return min(max((int) $request->query('per_page', 15), 1), 100);
    }
}
