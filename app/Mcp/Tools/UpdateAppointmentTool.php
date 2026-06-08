<?php

namespace App\Mcp\Tools;

use App\Services\EasyAppointmentsClient;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;

#[Description('Update one or more fields on an existing appointment.')]
class UpdateAppointmentTool extends Tool
{
    public function __construct(private readonly EasyAppointmentsClient $client) {}

    public function schema(JsonSchema $schema): array
    {
        return [
            'appointment_id' => $schema->integer()->description('Appointment ID to update.')->required(),
            'start'          => $schema->string()->description('New start datetime (YYYY-MM-DD HH:MM:SS).'),
            'end'            => $schema->string()->description('New end datetime (YYYY-MM-DD HH:MM:SS).'),
            'notes'          => $schema->string()->description('Updated notes.'),
            'provider_id'    => $schema->integer()->description('Reassign to a different provider.'),
        ];
    }

    public function handle(Request $request): Response
    {
        $fields = array_filter([
            'start'      => $request->get('start'),
            'end'        => $request->get('end'),
            'notes'      => $request->get('notes'),
            'providerId' => $request->get('provider_id'),
        ], fn($v) => $v !== null);

        return Response::text(json_encode($this->client->put('appointments/' . $request->get('appointment_id'), $fields)));
    }
}
