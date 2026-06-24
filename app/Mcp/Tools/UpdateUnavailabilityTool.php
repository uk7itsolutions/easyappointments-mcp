<?php

namespace App\Mcp\Tools;

use App\Services\EasyAppointmentsClient;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;

#[Description('Update an existing unavailability record.')]
class UpdateUnavailabilityTool extends Tool
{
    public function __construct(private readonly EasyAppointmentsClient $client) {}

    public function schema(JsonSchema $schema): array
    {
        return [
            'unavailability_id' => $schema->integer()->description('Unavailability ID to update.')->required(),
            'start'             => $schema->string()->description('New start datetime (YYYY-MM-DD HH:MM:SS).'),
            'end'               => $schema->string()->description('New end datetime (YYYY-MM-DD HH:MM:SS).'),
            'provider_id'       => $schema->integer()->description('New provider ID.'),
            'notes'             => $schema->string()->description('Updated notes.'),
        ];
    }

    public function handle(Request $request): Response
    {
        $fields = array_filter([
            'start'      => $request->get('start'),
            'end'        => $request->get('end'),
            'providerId' => $request->get('provider_id'),
            'notes'      => $request->get('notes'),
        ], fn($v) => $v !== null);

        return Response::text(json_encode($this->client->put('unavailabilities/' . $request->get('unavailability_id'), $fields)));
    }
}
