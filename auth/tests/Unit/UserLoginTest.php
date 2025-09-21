<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\TestCase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Database\Capsule\Manager as DB;
use App\Modules\User\Application\DTOS\User\LoginDTO;
use App\Modules\User\Application\UseCases\User\UserUseCase;
use App\Modules\User\Infrastructure\Persistence\Models\User\User;
use App\Modules\User\Infrastructure\Persistence\Models\UserDevice\UserDevice;
use App\Modules\User\Infrastructure\Persistence\Repositories\User\UserRepository;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;

class UserLoginTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        // parent::setUp();
        Facade::setFacadeApplication($this->app);
        // // Boot the Laravel application
        // $app = require __DIR__ . '/../../bootstrap/app.php';
        // $app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

        // // Set the facade application
        // \Illuminate\Support\Facades\Facade::setFacadeApplication($app);

        // // Set up the database connection using Eloquent
        // $db = new \Illuminate\Database\Capsule\Manager();
        // $db->addConnection([
        //     'driver' => 'mysql',
        //     'host' => '127.0.0.1',
        //     'database' => 'generalAuth',
        //     'username' => 'root',
        //     'password' => 'password',
        //     'charset' => 'utf8mb4',
        //     'collation' => 'utf8mb4_unicode_ci',
        //     'prefix' => '',
        // ]);
        // $db->setAsGlobal();
        // $db->bootEloquent();

        // // Run migrations
        \Illuminate\Support\Facades\Artisan::call('migrate:fresh');
    }

    public function test_login_with_valid_credentials()
    {
        // Setup a user for login test
        $user = User::create([
            'email' => 'testuser@example.com',
            'phone' => null,
            'password' => Hash::make('testpassword'), // Use Hash to make sure the password is hashed
        ]);

        // Prepare the LoginDTO with valid credentials
        $loginDTO = new LoginDTO([
            'email' => 'testuser@example.com',
            'password' => 'testpassword',
            'device_token' => 'testDeviceToken',
            'device_id' => 'testDeviceId',
            'device_type' => 'mobile',
            'device_os' => 'Android',
            'device_os_version' => '10',
        ]);

        // Mock UserUseCase to simulate the login process
        $userRepository = $this->createMock(UserRepository::class);
        $userUseCase = new UserUseCase($userRepository);

        $userRepository->method('login')
            ->willReturn($user);

        $response = $userUseCase->login($loginDTO);

        // Assert that the response contains a valid API token and user data
        $this->assertTrue($response->getStatus());
        $this->assertNotNull($response->getData()["api_token"]);
        $this->assertEquals('testuser@example.com', $response->getData()["email"]);
    }

    public function test_login_with_invalid_credentials()
    {
        $user = User::create([
            'email' => 'testuser@example.com',
            'phone' => null,
            'password' => Hash::make('testpassword'),
        ]);

        $loginDTO = new LoginDTO([
            'credential' => 'testuser@example.com',
            'password' => 'wrongpassword',
            'device_token' => 'testDeviceToken',
            'device_id' => 'testDeviceId',
            'device_type' => 'mobile',
            'device_os' => 'Android',
            'device_os_version' => '10',
        ]);

        $userRepository = $this->createMock(UserRepository::class);
        $userRepository->expects($this->once())
            ->method('login')
            ->with($this->equalTo($loginDTO))
            ->willThrowException(new \Exception('The provided credentials are incorrect.'));

        $userUseCase = new UserUseCase($userRepository);
        $response = $userUseCase->login($loginDTO);

        $this->assertFalse($response->getStatus());
        $this->assertEquals('The provided credentials are incorrect.', $response->getMessage());
    }

    public function test_login_user_device_update()
    {
        $user = User::create([
            'email' => 'testuser@example.com',
            'phone' => null,
            'password' => Hash::make('testpassword'),
        ]);

        $loginDTO = new LoginDTO([
            'credential' => 'testuser@example.com',
            'password' => 'testpassword',
            'device_token' => 'testDeviceToken',
            'device_id' => 'testDeviceId',
            'device_type' => 'mobile',
            'device_os' => 'Android',
            'device_os_version' => '10',
        ]);

        $this->assertNotEmpty($loginDTO->UserDevice(), 'UserDevice() returned an empty array');

        $userRepository = $this->createMock(UserRepository::class);
        $userRepository->method('login')
            ->willReturn($user);



        $userUseCase = new UserUseCase($userRepository);
        $response = $userUseCase->login($loginDTO);

        $this->assertTrue($response->getStatus());
    }

    protected function tearDown(): void
    {
        // Restore PHPUnit's error handler
        restore_error_handler();
        restore_exception_handler();

        parent::tearDown();
    }
}
