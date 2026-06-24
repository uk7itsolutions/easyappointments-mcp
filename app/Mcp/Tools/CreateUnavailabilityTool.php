<?php

namespace App\Mcp\Tools;

use App\Services\EasyAppointmentsClient;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;

#[Description('Create a new unavailability record for a provider.')]
class CreateUnavailabilityTool extends Tool
{
    public function __construct(private readonly EasyAppointmentsClient $client) {}

    public function schema(JsonSchema $schema): array
    {
        return [
            'start_datetime' => $schema->string()->description('Start datetime (YYYY-MM-DD HH:MM:SS).')->required(),
            'end_datetime'   => $schema->string()->description('End datetime (YYYY-MM-DD HH:MM:SS).')->required(),
            'provider_id'    => $schema->integer()->description('Provider ID.')->required(),
            'notes'          => $schema->string()->description('Optional notes.')->default(''),
        ];
    }

    public function handle(Request $request): Response
    {
        $result = $this->client->post('unavailabilities', [
            'start'      => $request->get('start_datetime'),
            'end'        => $request->get('end_datetime'),
            'providerId' => $request->get('provider_id'),
            'notes'      => $request->get('notes', ''),
        ]);

        return Response::text(json_encode($result));
    }
}
