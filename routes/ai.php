<?php

use App\Mcp\Servers\EasyAppointmentsServer;
use Laravel\Mcp\Facades\Mcp;

Mcp::web('/mcp', EasyAppointmentsServer::class)
    ->middleware(['validate.ea.key']);
