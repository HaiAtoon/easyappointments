<?php

namespace Tests\Unit\Models;

use PHPUnit\Framework\TestCase;

require_once APPPATH . 'models/Providers_model.php';

class ProvidersModelTest extends TestCase
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
        $this->assertEquals('mobile_number', $api['mobile']);
        $this->assertEquals('is_private', $api['isPrivate']);
    }

    public function test_api_resource_includes_all_expected_fields(): void
    {
        $api = $this->getApiResource();

        $expected = ['id', 'firstName', 'lastName', 'email', 'mobile', 'phone', 'address', 'city', 'state', 'zip', 'timezone', 'language', 'notes', 'isPrivate'];

        foreach ($expected as $key) {
            $this->assertArrayHasKey($key, $api, "Missing API field: {$key}");
        }
    }

    private function getCasts(): array
    {
        $ref = new \ReflectionClass(\Providers_model::class);
        $prop = $ref->getProperty('casts');
        $prop->setAccessible(true);
        return $prop->getValue($ref->newInstanceWithoutConstructor());
    }

    private function getApiResource(): array
    {
        $ref = new \ReflectionClass(\Providers_model::class);
        $prop = $ref->getProperty('api_resource');
        $prop->setAccessible(true);
        return $prop->getValue($ref->newInstanceWithoutConstructor());
    }
}
