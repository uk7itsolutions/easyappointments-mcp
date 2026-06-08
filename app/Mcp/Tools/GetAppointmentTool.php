<?php

namespace App\Mcp\Tools;

use App\Services\EasyAppointmentsClient;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;

#[Description('Get full details for a single appointment by ID.')]
class GetAppointmentTool extends Tool
{
    public function __construct(private readonly EasyAppointmentsClient $client) {}

    public function schema(JsonSchema $schema): array
    {
        return [
            'appointment_id' => $schema->integer()->description('Appointment ID.')->required(),
        ];
    }

    public function handle(Request $request): Response
    {
        return Response::text(json_encode($this->client->get('appointments/' . $request->get('appointment_id'))));
    }
}
