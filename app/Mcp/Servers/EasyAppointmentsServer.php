<?php

namespace App\Mcp\Servers;

use App\Mcp\Tools\CancelAppointmentTool;
use App\Mcp\Tools\CheckAvailabilityTool;
use App\Mcp\Tools\CreateAppointmentTool;
use App\Mcp\Tools\CreateCustomerTool;
use App\Mcp\Tools\GetAppointmentTool;
use App\Mcp\Tools\GetCustomerTool;
use App\Mcp\Tools\GetSettingsTool;
use App\Mcp\Tools\ListAppointmentsTool;
use App\Mcp\Tools\ListProvidersTool;
use App\Mcp\Tools\ListServicesTool;
use App\Mcp\Tools\SearchCustomersTool;
use App\Mcp\Tools\UpdateAppointmentTool;
use App\Mcp\Tools\UpdateCustomerTool;
use Laravel\Mcp\Server;
use Laravel\Mcp\Server\Attributes\Instructions;
use Laravel\Mcp\Server\Attributes\Name;
use Laravel\Mcp\Server\Attributes\Version;

#[Name('EasyAppointments')]
#[Version('1.0.0')]
#[Instructions('Manage appointments, customers, services, and providers in EasyAppointments.')]
class EasyAppointmentsServer extends Server
{
    protected array $tools = [
        CheckAvailabilityTool::class,
        ListAppointmentsTool::class,
        GetAppointmentTool::class,
        CreateAppointmentTool::class,
        UpdateAppointmentTool::class,
        CancelAppointmentTool::class,
        SearchCustomersTool::class,
        GetCustomerTool::class,
        CreateCustomerTool::class,
        UpdateCustomerTool::class,
        ListServicesTool::class,
        ListProvidersTool::class,
        GetSettingsTool::class,
    ];
}
