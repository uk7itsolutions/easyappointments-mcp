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
            'provider_id'   => $schema->integer()->description('Provider ID (optional, omit to check all providers offering the service).'),
        ];
    }

    public function handle(Request $request): Response
    {
        $selectedDate = $request->get('selected_date');
        $serviceId    = $request->get('service_id');

        $providerIds = $request->has('provider_id')
            ? [$request->get('provider_id')]
            : collect($this->client->get('providers', ['serviceId' => $serviceId]))
                ->pluck('id')
                ->all();

        $results = [];

        foreach ($providerIds as $providerId) {
            $results[$providerId] = $this->client->get('availabilities', [
                'selected_date' => $selectedDate,
                'service_id'    => $serviceId,
                'provider_id'   => $providerId,
            ]);
        }

        return Response::text(json_encode($results));
    }
}
