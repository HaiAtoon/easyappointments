<?php

namespace Tests\Unit\Libraries;

use PHPUnit\Framework\TestCase;

require_once APPPATH . 'libraries/Pdf_utils.php';

class PdfUtilsTest extends TestCase
{
    public function test_get_pdf_password_returns_id_number_when_available(): void
    {
        $customer = ['id_number' => '123456789', 'phone_number' => '0501234567'];

        $this->assertEquals('123456789', \Pdf_utils::get_pdf_password($customer));
    }

    public function test_get_pdf_password_falls_back_to_phone_number(): void
    {
        $customer = ['id_number' => '', 'phone_number' => '0501234567'];

        $this->assertEquals('0501234567', \Pdf_utils::get_pdf_password($customer));
    }

    public function test_get_pdf_password_returns_empty_when_no_identifier(): void
    {
        $customer = ['id_number' => '', 'phone_number' => ''];

        $this->assertEquals('', \Pdf_utils::get_pdf_password($customer));
    }

    public function test_get_pdf_password_handles_missing_fields(): void
    {
        $customer = [];

        $this->assertEquals('', \Pdf_utils::get_pdf_password($customer));
    }

    public function test_get_password_field_returns_id_number_when_available(): void
    {
        $customer = ['id_number' => '123456789', 'phone_number' => '0501234567'];

        $this->assertEquals('id_number', \Pdf_utils::get_password_field($customer));
    }

    public function test_get_password_field_returns_phone_number_as_fallback(): void
    {
        $customer = ['id_number' => '', 'phone_number' => '0501234567'];

        $this->assertEquals('phone_number', \Pdf_utils::get_password_field($customer));
    }
}
