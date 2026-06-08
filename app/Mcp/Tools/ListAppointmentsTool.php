<?php

namespace App\Mcp\Tools;

use App\Services\EasyAppointmentsClient;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;

#[Description('List appointments, optionally filtered by date or customer.')]
class ListAppointmentsTool extends Tool
{
    public function __construct(private readonly EasyAppointmentsClient $client) {}

    public function schema(JsonSchema $schema): array
    {
        return [
            'date'        => $schema->string()->description('Filter by date (YYYY-MM-DD).'),
            'customer_id' => $schema->integer()->description('Filter by customer ID.'),
            'limit'       => $schema->integer()->description('Max results to return.')->default(25),
        ];
    }

    public function handle(Request $request): Response
    {
        $params = ['recordsPerPage' => $request->get('limit', 25)];

        if ($request->has('date')) {
            $params['q'] = $request->get('date');
        }

        if ($request->has('customer_id')) {
            $params['customerId'] = $request->get('customer_id');
        }

        return Response::text(json_encode($this->client->get('appointments', $params)));
    }
}
