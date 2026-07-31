<?php

namespace Tests\Unit;

use App\Http\Requests\DynamicPopupRequest;
use PHPUnit\Framework\TestCase;

class DynamicPopupRequestTest extends TestCase
{
    public function test_rules_match_the_dynamic_popup_form_limits(): void
    {
        $rules = (new DynamicPopupRequest())->rules();

        $this->assertSame('required|string|max:50', $rules['title']);
        $this->assertSame('required|string|max:200', $rules['summary']);
        $this->assertSame('required|string|max:30', $rules['btn_text']);
        $this->assertSame('required|in:white,dark', $rules['btn_text_color']);
        $this->assertContains('not_regex:/^\s*(?:javascript|data|vbscript):/i', $rules['btn_link']);
        $this->assertContains('regex:/^#[0-9a-fA-F]{6}$/', $rules['btn_background_color']);
    }
}
