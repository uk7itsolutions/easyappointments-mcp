<?php

namespace App\Mcp\Tools;

use App\Services\EasyAppointmentsClient;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;

#[Description('Return available time slots for a service on a given date.')]
class CheckAvailabilityTool extends Tool
{
    public function __construct(private readonly EasyAppointmentsClient $client) {}

    public function schema(JsonSchema $schema): array
    {
        return [
            'selected_date' => $schema->string()->description('Date in YYYY-MM-DD format.')->required(),
            'service_id'    => $schema->integer()->description('Service ID.')->required(),
            'provider_id'   => $schema->integer()->description('Provider ID (optional, omit for any provider.'),
        ];
    }

    public function handle(Request $request): Response
    {
        $params = ['selected_date' => $request->get('selected_date'), 'service_id' => $request->get('service_id')];

        if ($request->has('provider_id')) {
            $params['provider_id'] = $request->get('provider_id');
        }

        return Response::text(json_encode($this->client->get('availabilities', $params)));
    }
}
