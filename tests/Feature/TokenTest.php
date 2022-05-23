<?php

namespace Tests\Feature;

use App\Http\Controllers\API\TokenController;
use Tests\TestCase;

class TokenTest extends TestCase
{

    public function setUp(): void
    {
        parent::setUp();

        $this->tokenController = new TokenController;
    }
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testGetTokenIsValid()
    {
        $response = $this->get('/api/token');
        $response->assertOk();
        $response->assertHeader('content-type', 'application/json');
        $response->assertCookie('registration_token');
        $response->assertJsonPath('success', true);
        $response->assertCookieNotExpired('registration_token');
    }
}
