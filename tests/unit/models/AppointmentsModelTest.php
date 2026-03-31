<?php

namespace Tests\Unit\Models;

use PHPUnit\Framework\TestCase;

require_once APPPATH . 'models/Appointments_model.php';

class AppointmentsModelTest extends TestCase
{
    public function test_casts_array_has_expected_types(): void
    {
        $casts = $this->getCasts(\Appointments_model::class);

        $this->assertEquals('integer', $casts['id']);
        $this->assertEquals('boolean', $casts['is_unavailability']);
        $this->assertEquals('integer', $casts['id_users_provider']);
        $this->assertEquals('integer', $casts['id_users_customer']);
        $this->assertEquals('integer', $casts['id_services']);
    }

    public function test_api_resource_maps_camel_to_snake(): void
    {
        $api = $this->getApiResource(\Appointments_model::class);

        $this->assertEquals('start_datetime', $api['start']);
        $this->assertEquals('end_datetime', $api['end']);
        $this->assertEquals('id_services', $api['serviceId']);
        $this->assertEquals('id_users_provider', $api['providerId']);
        $this->assertEquals('id_users_customer', $api['customerId']);
    }

    public function test_api_resource_includes_all_public_fields(): void
    {
        $api = $this->getApiResource(\Appointments_model::class);

        $expected_keys = ['id', 'book', 'start', 'end', 'location', 'color', 'status', 'notes', 'serviceId', 'providerId', 'customerId'];

        foreach ($expected_keys as $key) {
            $this->assertArrayHasKey($key, $api, "Missing API field: {$key}");
        }
    }

    private function getCasts(string $class): array
    {
        $ref = new \ReflectionClass($class);
        $prop = $ref->getProperty('casts');
        $prop->setAccessible(true);
        return $prop->getValue($ref->newInstanceWithoutConstructor());
    }

    private function getApiResource(string $class): array
    {
        $ref = new \ReflectionClass($class);
        $prop = $ref->getProperty('api_resource');
        $prop->setAccessible(true);
        return $prop->getValue($ref->newInstanceWithoutConstructor());
    }
}
