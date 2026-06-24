<?php

namespace App\Mcp\Tools;

use App\Services\EasyAppointmentsClient;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;

#[Description('List unavailabilities, optionally filtered by provider or date.')]
class ListUnavailabilitiesTool extends Tool
{
    public function __construct(private readonly EasyAppointmentsClient $client) {}

    public function schema(JsonSchema $schema): array
    {
        return [
            'provider_id' => $schema->integer()->description('Filter by provider ID.'),
            'date'        => $schema->string()->description('Filter by date (YYYY-MM-DD).'),
            'limit'       => $schema->integer()->description('Max results to return.')->default(25),
        ];
    }

    public function handle(Request $request): Response
    {
        $params = ['recordsPerPage' => $request->get('limit', 25)];

        if ($request->has('provider_id')) {
            $params['providerId'] = $request->get('provider_id');
        }

        if ($request->has('date')) {
            $params['q'] = $request->get('date');
        }

        return Response::text(json_encode($this->client->get('unavailabilities', $params)));
    }
}
