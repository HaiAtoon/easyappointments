<?php

namespace Tests\Unit\Models;

use PHPUnit\Framework\TestCase;

require_once APPPATH . 'models/Services_model.php';

class ServicesModelTest extends TestCase
{
    public function test_casts_array(): void
    {
        $casts = $this->getCasts();

        $this->assertEquals('integer', $casts['id']);
        $this->assertEquals('float', $casts['price']);
        $this->assertEquals('integer', $casts['attendants_number']);
        $this->assertEquals('boolean', $casts['is_private']);
        $this->assertEquals('integer', $casts['id_service_categories']);
    }

    public function test_api_resource_maps_correctly(): void
    {
        $api = $this->getApiResource();

        $this->assertEquals('name', $api['name']);
        $this->assertEquals('duration', $api['duration']);
        $this->assertEquals('price', $api['price']);
        $this->assertEquals('id_service_categories', $api['serviceCategoryId']);
        $this->assertEquals('attendants_number', $api['attendantsNumber']);
    }

    private function getCasts(): array
    {
        $ref = new \ReflectionClass(\Services_model::class);
        $prop = $ref->getProperty('casts');
        $prop->setAccessible(true);
        return $prop->getValue($ref->newInstanceWithoutConstructor());
    }

    private function getApiResource(): array
    {
        $ref = new \ReflectionClass(\Services_model::class);
        $prop = $ref->getProperty('api_resource');
        $prop->setAccessible(true);
        return $prop->getValue($ref->newInstanceWithoutConstructor());
    }
}
