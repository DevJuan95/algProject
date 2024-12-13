<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

beforeEach(function () {
    $this->controller = new AuthController();
});
describe('AuthController@login', function (){
    it('fails to log in with invalid credentials', function () {
        $request = Request::create('/login', 'POST', [
            'email' => 'test@example.com',
            'password' => 'invalid-password'
        ]);

        JWTAuth::shouldReceive('attempt')
            ->once()
            ->with(['email' => 'test@example.com', 'password' => 'invalid-password'])
            ->andThrow(new JWTException('Something went wrong'));

        $response = $this->controller->login($request);
        expect($response->getStatusCode())->toBe(500);

        JWTAuth::shouldHaveReceived('attempt')
            ->once()
            ->with(['email' => 'test@example.com', 'password' => 'invalid-password']);
    });

});

describe('AuthController@getUser', function (){
    it('fails to get user with invalid token', function () {
        JWTAuth::shouldReceive('parseToken')
            ->once()
            ->andThrow(new JWTException('Invalid token'));

        $response = $this->controller->getUser();
        expect($response->getStatusCode())->toBe(400);

        JWTAuth::shouldHaveReceived('parseToken')
            ->once();
    });

    it('should handle the JWTExecption', function () {
        JWTAuth::shouldReceive('parseToken')
            ->once()
            ->andReturnSelf();
        JWTAuth::shouldReceive('authenticate')
            ->once()
            ->andThrow(new JWTException('Something went wrong'));

        $response = $this->controller->getUser();
        expect($response->getStatusCode())->toBe(400);

        JWTAuth::shouldHaveReceived('parseToken')
            ->once();
        JWTAuth::shouldHaveReceived('authenticate')
            ->once();
    });
});



