<?php

namespace App\Mcp\Tools;

use App\Services\EasyAppointmentsClient;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;

#[Description('Search customers by name, email, or phone.')]
class SearchCustomersTool extends Tool
{
    public function __construct(private readonly EasyAppointmentsClient $client) {}

    public function schema(JsonSchema $schema): array
    {
        return [
            'query' => $schema->string()->description('Search term (name, email, or phone).')->required(),
            'limit' => $schema->integer()->description('Max results to return.')->default(10),
        ];
    }

    public function handle(Request $request): Response
    {
        return Response::text(json_encode($this->client->get('customers', [
            'q'             => $request->get('query'),
            'recordsPerPage' => $request->get('limit', 10),
        ])));
    }
}
