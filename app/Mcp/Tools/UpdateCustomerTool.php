<?php

namespace App\Mcp\Tools;

use App\Services\EasyAppointmentsClient;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;

#[Description('Update fields on an existing customer record.')]
class UpdateCustomerTool extends Tool
{
    public function __construct(private readonly EasyAppointmentsClient $client) {}

    public function schema(JsonSchema $schema): array
    {
        return [
            'customer_id' => $schema->integer()->description('Customer ID to update.')->required(),
            'first_name'  => $schema->string()->description('Updated first name.'),
            'last_name'   => $schema->string()->description('Updated last name.'),
            'email'       => $schema->string()->description('Updated email address.'),
            'phone'       => $schema->string()->description('Updated phone number.'),
            'notes'       => $schema->string()->description('Updated notes.'),
        ];
    }

    public function handle(Request $request): Response
    {
        $fields = array_filter([
            'firstName' => $request->get('first_name'),
            'lastName'  => $request->get('last_name'),
            'email'     => $request->get('email'),
            'phone'     => $request->get('phone'),
            'notes'     => $request->get('notes'),
        ], fn($v) => $v !== null);

        return Response::text(json_encode($this->client->put('customers/' . $request->get('customer_id'), $fields)));
    }
}
