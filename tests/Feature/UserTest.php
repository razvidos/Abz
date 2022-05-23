<?php

namespace Tests\Feature;

use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class UserTest extends TestCase
{

    public function testGetUsersCorrectly()
    {
        $response = $this->get('/api/users?page=1');
        $response->assertOk();
        $response->assertHeader('content-type', 'application/json');

        $response->assertJson(['success' => true]);
        $response->assertJson(fn (AssertableJson $json) =>
            $json->hasAll(
                'page', 'count',
                'total_pages', 'total_users',
                'links', 'users'
            )->etc()
        );
    }

    public function testGetUserCorrectly()
    {
        $response = $this->get('/api/users/1');
        $response->assertOk();
        $response->assertHeader('content-type', 'application/json');

        $response->assertJson(['success' => true]);
        $response->assertJson(fn (AssertableJson $json) =>
            $json->hasAll('user')->etc()
        );
    }

    public function testGetUserIdIsNotInteger()
    {
        $response = $this->get('/api/users/a');
        $response->assertStatus(400);
        $response->assertHeader('content-type', 'application/json');

        $response->assertJson(['success' => false]);
        $response->assertJson(['message' => 'Validation failed']);
        $response->assertJsonPath('fails.user_id', 'The user_id must be an integer.');
    }

    public function testGetUserIdIsTooBigger()
    {
        $response = $this->get('/api/users/999999999');
        $response->assertStatus(404);
        $response->assertHeader('content-type', 'application/json');

        $response->assertJson(['success' => false]);
        $response->assertJson(['message' => 'The user with the requested identifier does not exist']);
        $response->assertJsonPath('fails.user_id', 'User not found');
    }

    public function testGetUsersPageEmpty()
    {
        $response = $this->get('/api/users?page');
        $response->assertStatus(422);
        $response->assertHeader('content-type', 'application/json');
        $response->assertJsonPath('success', false);
    }

    public function testGetUsersEmpty() {
        $response = $this->get('/api/users?');
        $response->assertStatus(422);
        $response->assertHeader('content-type', 'application/json');
        $response->assertJsonPath('success', false);
    }

    public function testGetUsersPage0() {
        $response = $this->get('/api/users?page=0');
        $response->assertStatus(404);
        $response->assertHeader('content-type', 'application/json');
        $response->assertJsonPath('success', false);
    }

    public function testGetUsersOffsetMinus1() {
        $response = $this->get('/api/users?offset=-1');
        $response->assertStatus(422);
        $response->assertHeader('content-type', 'application/json');
        $response->assertJsonPath('success', false);
    }

    public function testGetUsersPage1Count0() {
        $response = $this->get('/api/users?page=1&count=0');
        $response->assertStatus(422);
        $response->assertHeader('content-type', 'application/json');
        $response->assertJsonPath('success', false);
    }

    public function testGetUsersPage1Count101() {
        $response = $this->get('/api/users?page=1&count=101');
        $response->assertStatus(422);
        $response->assertHeader('content-type', 'application/json');
        $response->assertJsonPath('success', false);
    }

    public function testPostUsersWithoutToken() {
        $response = $this->post('/api/users');
        $response->assertStatus(401);
        $response->assertHeader('content-type', 'application/json');
        $response->assertJson(['success' => false, "message" => "The token expired."]);
    }

    public function testPostUsersWithTokenWithoutFields() {
        $this->withCookie(
            'registration_token',
            'qq');
        $response = $this->post('/api/users');
        $response->assertStatus(422);
        $response->assertHeader('content-type', 'application/json');
        $response->assertJson(['success' => false]);
        $response->assertJson(['message' => "Validation failed"]);
    }
}
