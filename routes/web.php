<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        'name' => 'EasyAppointments MCP Server',
        'mcp_endpoint' => url('/mcp'),
    ]);
});
