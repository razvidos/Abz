<?php

namespace Tests\Feature;

use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class UserPositionTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testGetPositionsCorrectly()
    {
        $response = $this->get('/api/positions');

        $response->assertOk();
        $response->assertHeader('content-type', 'application/json');

        $response->assertJson(['success' => true]);
        $response->assertJson(fn (AssertableJson $json) =>
            $json->has('positions')->etc()
        );
    }

    public function testGetPositionCorrectly()
    {
        $response = $this->get('/api/positions/1');

        $response->assertOk();
        $response->assertHeader('content-type', 'application/json');

        $response->assertJson(['success' => true]);
        $response->assertJson(fn (AssertableJson $json) =>
            $json->has('position')->etc()
        );
    }

    public function testGetPositionIdIsTooBigger()
    {
        $response = $this->get('/api/positions/999999999');

        $response->assertNotFound();
        $response->assertHeader('content-type', 'application/json');

        $response->assertJson(['success' => false]);
        $response->assertJson(['message' => 'Positions not found']);
    }
}
