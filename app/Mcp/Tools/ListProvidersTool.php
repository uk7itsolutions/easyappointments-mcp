<?php

namespace App\Mcp\Tools;

use App\Services\EasyAppointmentsClient;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;

#[Description('List all providers, optionally filtered by service.')]
class ListProvidersTool extends Tool
{
    public function __construct(private readonly EasyAppointmentsClient $client) {}

    public function schema(JsonSchema $schema): array
    {
        return [
            'service_id' => $schema->integer()->description('Filter providers by service ID.'),
        ];
    }

    public function handle(Request $request): Response
    {
        $params = $request->has('service_id') ? ['serviceId' => $request->get('service_id')] : [];

        return Response::text(json_encode($this->client->get('providers', $params)));
    }
}
