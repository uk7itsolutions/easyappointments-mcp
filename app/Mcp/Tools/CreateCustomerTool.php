<?php

namespace App\Mcp\Tools;

use App\Services\EasyAppointmentsClient;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;

#[Description('Create a new customer record in EasyAppointments.')]
class CreateCustomerTool extends Tool
{
    public function __construct(private readonly EasyAppointmentsClient $client) {}

    public function schema(JsonSchema $schema): array
    {
        return [
            'first_name' => $schema->string()->description('Customer first name.')->required(),
            'last_name'  => $schema->string()->description('Customer last name.')->required(),
            'email'      => $schema->string()->description('Customer email address.')->required(),
            'phone'      => $schema->string()->description('Customer phone number.')->default(''),
            'notes'      => $schema->string()->description('Optional notes about the customer.')->default(''),
        ];
    }

    public function handle(Request $request): Response
    {
        return Response::text(json_encode($this->client->post('customers', [
            'firstName' => $request->get('first_name'),
            'lastName'  => $request->get('last_name'),
            'email'     => $request->get('email'),
            'phone'     => $request->get('phone', ''),
            'notes'     => $request->get('notes', ''),
        ])));
    }
}
