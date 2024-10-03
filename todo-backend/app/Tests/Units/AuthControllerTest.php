<?php

namespace Tests\Unit;

use App\Http\Controllers\AuthController;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_register_success()
    {
        $request = Request::create('/register', 'POST', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $controller = new AuthController();
        $response = $controller->register($request);

        $this->assertEquals(200, $response->status());
        $this->assertArrayHasKey('token', $response->getData(true));
    }

    public function test_register_missing_fields()
    {
        $request = Request::create('/register', 'POST', [
            'name' => 'John Doe',
        ]);

        $controller = new AuthController();
        $response = $controller->register($request);

        $this->assertEquals(422, $response->status());
    }

    public function test_register_invalid_email()
    {
        $request = Request::create('/register', 'POST', [
            'name' => 'John Doe',
            'email' => 'invalid-email',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $controller = new AuthController();
        $response = $controller->register($request);

        $this->assertEquals(422, $response->status());
    }

    public function test_register_short_password()
    {
        $request = Request::create('/register', 'POST', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'short',
            'password_confirmation' => 'short',
        ]);

        $controller = new AuthController();
        $response = $controller->register($request);

        $this->assertEquals(422, $response->status());
    }

    public function test_login_success()
    {
        $user = User::factory()->create([
            'email' => 'john@example.com',
            'password' => Hash::make('password'),
        ]);

        $request = Request::create('/login', 'POST', [
            'email' => 'john@example.com',
            'password' => 'password',
        ]);

        $controller = new AuthController();
        $response = $controller->login($request);

        $this->assertEquals(200, $response->status());
        $this->assertArrayHasKey('token', $response->getData(true));
    }

    public function test_login_incorrect_credentials()
    {
        $request = Request::create('/login', 'POST', [
            'email' => 'john@example.com',
            'password' => 'wrongpassword',
        ]);

        $controller = new AuthController();
        $response = $controller->login($request);

        $this->assertEquals(401, $response->status());
        $this->assertArrayHasKey('error', $response->getData(true));
    }

    public function test_logout_success()
    {
        Auth::shouldReceive('logout')->once();

        $controller = new AuthController();
        $response = $controller->logout();

        $this->assertEquals(200, $response->status());
        $this->assertEquals(['message' => 'Successfully logged out'], $response->getData(true));
    }
}