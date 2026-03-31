<?php

namespace Tests\Unit\Models;

use PHPUnit\Framework\TestCase;

require_once APPPATH . 'models/Customers_model.php';

class CustomersModelTest extends TestCase
{
    public function test_casts_array(): void
    {
        $casts = $this->getCasts();

        $this->assertEquals('integer', $casts['id']);
        $this->assertEquals('integer', $casts['id_roles']);
    }

    public function test_api_resource_maps_correctly(): void
    {
        $api = $this->getApiResource();

        $this->assertEquals('first_name', $api['firstName']);
        $this->assertEquals('last_name', $api['lastName']);
        $this->assertEquals('phone_number', $api['phone']);
        $this->assertEquals('zip_code', $api['zip']);
        $this->assertEquals('id_number', $api['idNumber']);
    }

    public function test_api_resource_includes_custom_fields(): void
    {
        $api = $this->getApiResource();

        $this->assertArrayHasKey('customField1', $api);
        $this->assertArrayHasKey('customField2', $api);
        $this->assertArrayHasKey('customField3', $api);
        $this->assertArrayHasKey('customField4', $api);
        $this->assertArrayHasKey('customField5', $api);
    }

    public function test_api_encode_transforms_to_camel_case(): void
    {
        $model = $this->getInstance();

        $customer = [
            'id' => 1,
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com',
            'phone_number' => '050',
            'address' => '1 St',
            'city' => 'TLV',
            'zip_code' => '12345',
            'id_number' => '999',
            'notes' => 'Test',
            'timezone' => 'UTC',
            'language' => 'english',
            'custom_field_1' => 'a',
            'custom_field_2' => 'b',
            'custom_field_3' => 'c',
            'custom_field_4' => 'd',
            'custom_field_5' => 'e',
            'ldap_dn' => '',
        ];

        $model->api_encode($customer);

        $this->assertArrayHasKey('firstName', $customer);
        $this->assertArrayHasKey('lastName', $customer);
        $this->assertEquals('John', $customer['firstName']);
        $this->assertArrayNotHasKey('first_name', $customer);
    }

    public function test_api_decode_transforms_to_snake_case(): void
    {
        $model = $this->getInstance();

        $customer = [
            'firstName' => 'Jane',
            'lastName' => 'Smith',
            'email' => 'jane@example.com',
            'phone' => '051',
        ];

        $model->api_decode($customer);

        $this->assertArrayHasKey('first_name', $customer);
        $this->assertEquals('Jane', $customer['first_name']);
        $this->assertEquals('051', $customer['phone_number']);
        $this->assertArrayNotHasKey('firstName', $customer);
    }

    public function test_api_decode_with_base_merges(): void
    {
        $model = $this->getInstance();

        $base = ['first_name' => 'Old', 'last_name' => 'Name', 'email' => 'old@test.com'];
        $update = ['firstName' => 'New'];

        $model->api_decode($update, $base);

        $this->assertEquals('New', $update['first_name']);
        $this->assertEquals('Name', $update['last_name']);
        $this->assertEquals('old@test.com', $update['email']);
    }

    private function getCasts(): array
    {
        $ref = new \ReflectionClass(\Customers_model::class);
        $prop = $ref->getProperty('casts');
        $prop->setAccessible(true);
        return $prop->getValue($ref->newInstanceWithoutConstructor());
    }

    private function getApiResource(): array
    {
        $ref = new \ReflectionClass(\Customers_model::class);
        $prop = $ref->getProperty('api_resource');
        $prop->setAccessible(true);
        return $prop->getValue($ref->newInstanceWithoutConstructor());
    }

    private function getInstance(): \Customers_model
    {
        return (new \ReflectionClass(\Customers_model::class))->newInstanceWithoutConstructor();
    }
}
