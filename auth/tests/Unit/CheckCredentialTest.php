<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase;
use Illuminate\Support\Facades\Hash;
use App\Modules\User\Application\DTOS\User\CheckCredentialDTO;
use App\Modules\User\Application\UseCases\User\UserUseCase;
use App\Modules\User\Infrastructure\Persistence\Models\User\User;
use App\Modules\User\Infrastructure\Persistence\Repositories\User\UserRepository;

class CheckCredentialTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
    }
    public function test_check_credential_with_valid_email()
    {
        // Create a mock user object
        $user = $this->createMock(User::class);

        // Expect that sendOtp will be called exactly once
        $user->expects($this->once()) // Expect sendOtp to be called once
             ->method('sendOtp')
             ->with($this->anything(), $this->anything()); // No need for a return value

        // Prepare CheckCredentialDTO with valid email
        $checkCredentialDTO = new CheckCredentialDTO([
            'email' => 'testuser@gmail.com',
            'phone' => null,
        ]);

        // Mock the UserRepository to return the mocked user
        $userRepository = $this->createMock(UserRepository::class);
        $userRepository->method('checkCredential')
            ->with($this->equalTo($checkCredentialDTO))
            ->willReturn($user);

        // Create UserUseCase instance
        $userUseCase = new UserUseCase($userRepository);

        // Call the checkCredential method and get the response
        $response = $userUseCase->checkCredential($checkCredentialDTO);

        // Assert the expected results
        $this->assertTrue($response->getStatus());
        $this->assertEquals('success', $response->getMessage());
        $this->assertTrue($response->getData());
    }





    public function test_check_credential_with_invalid_email()
    {
        // Prepare CheckCredentialDTO with invalid email
        $checkCredentialDTO = new CheckCredentialDTO([
            'credential' => 'invalid@example.com',
        ]);

        // Mock UserRepository to throw exception
        $userRepository = $this->createMock(UserRepository::class);
        $userRepository->expects($this->once())
            ->method('checkCredential')
            ->with($this->equalTo($checkCredentialDTO))
            ->willThrowException(new \Exception('The provided credentials are incorrect.'));

        // Create UserUseCase instance
        $userUseCase = new UserUseCase($userRepository);
        $response = $userUseCase->checkCredential($checkCredentialDTO);

        // Assert response
        $this->assertFalse($response->getStatus());
        $this->assertEquals('The provided credentials are incorrect.', $response->getMessage());
        $this->assertEmpty($response->getData());
    }

    public function test_check_credential_with_unexpected_exception()
    {
        // Prepare CheckCredentialDTO
        $checkCredentialDTO = new CheckCredentialDTO([
            'credential' => 'testuser@example.com',
        ]);

        // Mock UserRepository to throw unexpected exception
        $userRepository = $this->createMock(UserRepository::class);
        $userRepository->expects($this->once())
            ->method('checkCredential')
            ->with($this->equalTo($checkCredentialDTO))
            ->willThrowException(new \Exception('Unexpected error'));

        // Create UserUseCase instance
        $userUseCase = new UserUseCase($userRepository);
        $response = $userUseCase->checkCredential($checkCredentialDTO);

        // Assert response
        $this->assertFalse($response->getStatus());
        $this->assertEquals('Unexpected error', $response->getMessage());
        $this->assertEmpty($response->getData());
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }
}
