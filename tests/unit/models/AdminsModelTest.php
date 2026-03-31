<?php

namespace Tests\Unit\Models;

use PHPUnit\Framework\TestCase;

require_once APPPATH . 'models/Admins_model.php';

class AdminsModelTest extends TestCase
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
    }

    public function test_api_resource_includes_role_id(): void
    {
        $api = $this->getApiResource();

        $this->assertArrayHasKey('roleId', $api);
        $this->assertEquals('id_roles', $api['roleId']);
    }

    private function getCasts(): array
    {
        $ref = new \ReflectionClass(\Admins_model::class);
        $prop = $ref->getProperty('casts');
        $prop->setAccessible(true);
        return $prop->getValue($ref->newInstanceWithoutConstructor());
    }

    private function getApiResource(): array
    {
        $ref = new \ReflectionClass(\Admins_model::class);
        $prop = $ref->getProperty('api_resource');
        $prop->setAccessible(true);
        return $prop->getValue($ref->newInstanceWithoutConstructor());
    }
}
