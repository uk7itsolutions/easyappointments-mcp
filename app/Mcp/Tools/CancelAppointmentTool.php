<?php

namespace App\Mcp\Tools;

use App\Services\EasyAppointmentsClient;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;

#[Description('Cancel (delete) an appointment by ID.')]
class CancelAppointmentTool extends Tool
{
    public function __construct(private readonly EasyAppointmentsClient $client) {}

    public function schema(JsonSchema $schema): array
    {
        return [
            'appointment_id' => $schema->integer()->description('Appointment ID to cancel.')->required(),
        ];
    }

    public function handle(Request $request): Response
    {
        $id = $request->get('appointment_id');
        $this->client->delete("appointments/{$id}");

        return Response::text("Appointment {$id} cancelled.");
    }
}
