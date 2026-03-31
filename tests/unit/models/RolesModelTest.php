<?php

namespace Tests\Unit\Models;

use PHPUnit\Framework\TestCase;

require_once APPPATH . 'models/Roles_model.php';

class RolesModelTest extends TestCase
{
    public function test_casts_array(): void
    {
        $casts = $this->getCasts();

        $this->assertEquals('integer', $casts['id']);
        $this->assertEquals('boolean', $casts['is_admin']);
        $this->assertEquals('integer', $casts['appointments']);
        $this->assertEquals('integer', $casts['customers']);
        $this->assertEquals('integer', $casts['services']);
        $this->assertEquals('integer', $casts['users']);
        $this->assertEquals('integer', $casts['system_settings']);
        $this->assertEquals('integer', $casts['user_settings']);
    }

    public function test_permission_constants_exist(): void
    {
        $this->assertEquals('appointments', PRIV_APPOINTMENTS);
        $this->assertEquals('customers', PRIV_CUSTOMERS);
        $this->assertEquals('services', PRIV_SERVICES);
        $this->assertEquals('users', PRIV_USERS);
        $this->assertEquals('system_settings', PRIV_SYSTEM_SETTINGS);
        $this->assertEquals('user_settings', PRIV_USER_SETTINGS);
    }

    public function test_role_slug_constants_exist(): void
    {
        $this->assertEquals('admin', DB_SLUG_ADMIN);
        $this->assertEquals('provider', DB_SLUG_PROVIDER);
        $this->assertEquals('secretary', DB_SLUG_SECRETARY);
        $this->assertEquals('customer', DB_SLUG_CUSTOMER);
    }

    private function getCasts(): array
    {
        $ref = new \ReflectionClass(\Roles_model::class);
        $prop = $ref->getProperty('casts');
        $prop->setAccessible(true);
        return $prop->getValue($ref->newInstanceWithoutConstructor());
    }
}
