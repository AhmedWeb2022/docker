<?php

namespace Tests\Unit;

use Mockery;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Modules\Base\Application\Response\DataStatus;
use App\Modules\User\Application\DTOS\User\RegisterDTO;
use App\Modules\User\Application\UseCases\User\UserUseCase;
use App\Modules\User\Infrastructure\Persistence\Models\User\User;
use App\Modules\User\Infrastructure\Persistence\Repositories\User\UserRepository;

class UserRegisterTest extends TestCase
{
    use WithFaker;

    protected $userUseCase;
    protected $userRepository;

    protected function setUp(): void
    {
        parent::setUp();

        // Mock the repository
        $this->userRepository = Mockery::mock(UserRepository::class);

        // Instantiate the use case with mocked repository
        $this->userUseCase = new UserUseCase($this->userRepository);
    }

    /** @test */
    /** @test */
    public function it_registers_a_user_successfully()
    {
        // Arrange: Create a real instance of RegisterDTO
        $dto = new RegisterDTO([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '1234567890',
            'identify_number' => '12345678',
            'password' => 'password123',
            'device_token' => 'fake-device-token',
            'device_id' => 'fake-device-id',
            'device_type' => 'mobile',
            'device_os' => 'iOS',
            'device_os_version' => '14.0',
            'stage_id' => null,
            'location_id' => null,
            'nationality_id' => null,
            'phone_code' => '+1',
        ]);

        // Mock the user repository and return a mocked user model
        $user = Mockery::mock(User::class);

        // Mock userDevices method to return a mock of the relationship
        $userDevices = Mockery::mock();
        $user->shouldReceive('userDevices')->once()->andReturn($userDevices);
        $userDevices->shouldReceive('create')->once();

        // Mock the ArrayAccess behavior for multiple offsetSet calls
        $user->shouldReceive('offsetSet')->times(4); // Expecting 4 calls to offsetSet

        // Mock the user repository to return the mocked user
        $this->userRepository->shouldReceive('create')->once()->andReturn($user);

        // Mock methods on the User model
        $user->shouldReceive('createToken')->once()->andReturn((object)['plainTextToken' => 'fake-token']);
        $user->shouldReceive('sendOtp')->once();
        $user->shouldReceive('refresh')->andReturnSelf();

        // Act: Register the user using the real DTO
        $response = $this->userUseCase->register($dto);

        // Assert: Check the response is as expected
        $this->assertInstanceOf(DataStatus::class, $response);
        $this->assertTrue($response->getStatus());
        $this->assertEquals('success', $response->getMessage());
    }


    /** @test */
    public function it_handles_registration_exception()
    {
        // Arrange: Create a real instance of RegisterDTO
        $dto = new RegisterDTO([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '1234567890',
            'identify_number' => '12345678',
            'password' => 'password123',
        ]);

        // Mock the repository to throw an exception
        $this->userRepository->shouldReceive('create')->andThrow(new \Exception('Failed to create user'));

        // Act: Register the user using the real DTO
        $response = $this->userUseCase->register($dto);

        // Assert: Check the response is as expected
        $this->assertInstanceOf(DataStatus::class, $response);
        $this->assertFalse($response->getStatus());
        $this->assertEquals('Failed to create user', $response->getMessage());
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
