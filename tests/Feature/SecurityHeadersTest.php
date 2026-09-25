<?php

namespace Tests\Feature;

use Tests\TestCase;

class SecurityHeadersTest extends TestCase
{
    public function test_html_responses_include_security_headers(): void
    {
        $response = $this->get(route('home'));

        $response
            ->assertOk()
            ->assertHeader('cross-origin-opener-policy', 'same-origin')
            ->assertHeader('cross-origin-resource-policy', 'same-origin')
            ->assertHeader('referrer-policy', 'strict-origin-when-cross-origin')
            ->assertHeader('x-content-type-options', 'nosniff')
            ->assertHeader('x-frame-options', 'SAMEORIGIN')
            ->assertHeader('x-permitted-cross-domain-policies', 'none');

        $contentSecurityPolicy = (string) $response->headers->get('content-security-policy');

        $this->assertStringContainsString("object-src 'none'", $contentSecurityPolicy);
        $this->assertStringContainsString("form-action 'self'", $contentSecurityPolicy);
        $this->assertStringContainsString("frame-ancestors 'self'", $contentSecurityPolicy);
    }

    public function test_secure_responses_enable_hsts(): void
    {
        $this->withHeader('X-Forwarded-Proto', 'https')
            ->get(route('home'))
            ->assertHeader('strict-transport-security', 'max-age=31536000; includeSubDomains');
    }
}
