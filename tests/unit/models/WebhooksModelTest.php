<?php

namespace Tests\Unit\Models;

use PHPUnit\Framework\TestCase;

require_once APPPATH . 'models/Webhooks_model.php';

class WebhooksModelTest extends TestCase
{
    public function test_casts_array(): void
    {
        $casts = $this->getCasts();

        $this->assertEquals('integer', $casts['id']);
        $this->assertEquals('boolean', $casts['is_active']);
        $this->assertEquals('boolean', $casts['is_ssl_verified']);
    }

    public function test_api_resource_maps_correctly(): void
    {
        $api = $this->getApiResource();

        $this->assertArrayHasKey('name', $api);
        $this->assertArrayHasKey('url', $api);
        $this->assertArrayHasKey('action', $api);
        $this->assertArrayHasKey('isActive', $api);
        $this->assertArrayHasKey('isSslVerified', $api);
        $this->assertArrayHasKey('secretToken', $api);
    }

    private function getCasts(): array
    {
        $ref = new \ReflectionClass(\Webhooks_model::class);
        $prop = $ref->getProperty('casts');
        $prop->setAccessible(true);
        return $prop->getValue($ref->newInstanceWithoutConstructor());
    }

    private function getApiResource(): array
    {
        $ref = new \ReflectionClass(\Webhooks_model::class);
        $prop = $ref->getProperty('api_resource');
        $prop->setAccessible(true);
        return $prop->getValue($ref->newInstanceWithoutConstructor());
    }
}
