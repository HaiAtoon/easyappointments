<?php

namespace Tests\Unit\Models;

use PHPUnit\Framework\TestCase;

require_once APPPATH . 'models/Blocked_periods_model.php';

class BlockedPeriodsModelTest extends TestCase
{
    public function test_casts_array(): void
    {
        $casts = $this->getCasts();

        $this->assertEquals('integer', $casts['id']);
    }

    public function test_api_resource_maps_correctly(): void
    {
        $api = $this->getApiResource();

        $this->assertEquals('name', $api['name']);
        $this->assertEquals('start_datetime', $api['start']);
        $this->assertEquals('end_datetime', $api['end']);
        $this->assertEquals('notes', $api['notes']);
    }

    private function getCasts(): array
    {
        $ref = new \ReflectionClass(\Blocked_periods_model::class);
        $prop = $ref->getProperty('casts');
        $prop->setAccessible(true);
        return $prop->getValue($ref->newInstanceWithoutConstructor());
    }

    private function getApiResource(): array
    {
        $ref = new \ReflectionClass(\Blocked_periods_model::class);
        $prop = $ref->getProperty('api_resource');
        $prop->setAccessible(true);
        return $prop->getValue($ref->newInstanceWithoutConstructor());
    }
}
