<?php

namespace App\Mcp\Tools;

use App\Services\EasyAppointmentsClient;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;

#[Description('Delete an unavailability record.')]
class DeleteUnavailabilityTool extends Tool
{
    public function __construct(private readonly EasyAppointmentsClient $client) {}

    public function schema(JsonSchema $schema): array
    {
        return [
            'unavailability_id' => $schema->integer()->description('Unavailability ID to delete.')->required(),
        ];
    }

    public function handle(Request $request): Response
    {
        $id = $request->get('unavailability_id');
        $this->client->delete("unavailabilities/{$id}");

        return Response::text("Unavailability {$id} deleted.");
    }
}
