<?php

use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;

describe('Authentication E2E test', function (){
    it('Should get the token for a
        user and the user information', function () {
        $user = User::factory()->create();
        $response = $this
            ->post('/login', [
                'email' => $user->email,
                'password' => 'password'
            ]);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'token',
            'user'
        ]);
        $response->assertJsonFragment([
            'email' => $user->email
        ]);
    });

    it('should not get the token for a
        user with invalid credentials', function () {
        $user = User::factory()->create();
        $response = $this
            ->post('/login', [
                'email' => $user->email,
                'password' => 'invalid-password'
            ]);
        $response->assertStatus(422);
    });

    it('get the user information', function () {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);
        $response = $this
            ->withHeader('Authorization', "Bearer $token")
            ->get('/user');
        $response->assertStatus(200);
        $response->assertJsonFragment([
            'email' => $user->email
        ]);
    });

    it('should not get the user
        information with invalid token', function () {
        $response = $this
            ->withHeader("Authorization", "Bearer invalid-token")
            ->get('/user');
        $response->assertStatus(401);
    });

    it('throw an error when trying to get the
        user information without a token', function () {
        $response = $this
            ->get('/user');
        $response->assertStatus(401);
    });

    it('should logout the user', function () {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);
        $response = $this
            ->withHeader('Authorization', "Bearer $token")
            ->post('/logout');
        $response->assertStatus(200);
    });
});
