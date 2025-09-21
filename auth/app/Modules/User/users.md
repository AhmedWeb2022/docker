Below is a detailed documentation for your provided PHP Laravel application code, focusing on the user authentication and management module. This documentation will cover the endpoint workflows, explain key functions, describe database tables and their relationships, and outline how the Domain-Driven Design (DDD) structure is implemented.

---

## API Documentation: User Authentication and Management Module

### Overview
This module handles user authentication, registration, password management, and account management functionalities for a Laravel-based application. It follows a **Domain-Driven Design (DDD)** approach with separation of concerns across layers (Application, Domain, Infrastructure). The system uses DTOs (Data Transfer Objects) for data validation and transfer, Laravel Sanctum for API token-based authentication, and integrates WhatsApp messaging for OTP delivery.

---

### Endpoint Workflow

#### 1. Public API Endpoints (Unauthenticated)
These endpoints are accessible without authentication and handle user registration, login, and password reset workflows.

- **POST /api/register**
  - **Purpose**: Registers a new user.
  - **Request**: 
    - Body: `{ "name": "John Doe", "username": "johndoe", "phone": "1234567890", "identify_number": "ID123", "email": "john@example.com", "password": "secret123", "device_token": "token123", ... }`
  - **Workflow**:
    1. Validates input via `RegisterRequest`.
    2. Creates a `RegisterDTO` from validated data.
    3. `UserUseCase::register` creates a new user in the database and associates a device (if provided).
    4. Generates an API token and returns a `UserResource`.
  - **Response**: 
    - Success: `{ "status": true, "message": "success", "data": { "id": 1, "name": "John Doe", "api_token": "token" } }`
    - Failure: `{ "status": false, "message": "Error message" }`

- **POST /api/login**
  - **Purpose**: Authenticates a user and returns an API token.
  - **Request**: 
    - Body: `{ "credential": "john@example.com", "password": "secret123", "device_token": "token123", ... }`
  - **Workflow**:
    1. Validates input via `LoginRequest`.
    2. Creates a `LoginDTO`.
    3. `UserUseCase::login` verifies credentials and updates/creates device info.
    4. Returns a `UserResource` with an API token.
  - **Response**: Same as `/register`.

- **POST /api/check_credential**
  - **Purpose**: Initiates OTP verification for password reset or credential check.
  - **Request**: 
    - Body: `{ "credential": "john@example.com" }`
  - **Workflow**:
    1. Validates input via `CheckCredentialRequest`.
    2. Creates a `CheckCredentialDTO`.
    3. `UserUseCase::checkCredential` finds the user, generates an OTP, and sends it via email or WhatsApp.
    4. Stores OTP in cache for 5 minutes.
  - **Response**: 
    - Success: `{ "status": true, "message": "success", "data": true }`
    - Failure: `{ "status": false, "message": "Credential not found" }`

- **POST /api/check_code**
  - **Purpose**: Verifies an OTP code.
  - **Request**: 
    - Body: `{ "credential": "john@example.com", "code": "123456" }`
  - **Workflow**:
    1. Validates input via `CheckCodeRequest`.
    2. Creates a `CheckCodeDTO`.
    3. `UserUseCase::checkCode` compares the provided code with the cached OTP.
  - **Response**: 
    - Success: `{ "status": true, "message": "success", "data": true }`
    - Failure: `{ "status": false, "message": "Incorrect code" }`

- **POST /api/reset_password**
  - **Purpose**: Resets the user’s password using a valid OTP.
  - **Request**: 
    - Body: `{ "credential": "john@example.com", "code": "123456", "password": "newsecret123" }`
  - **Workflow**:
    1. Validates input via `ResetPasswordRequest`.
    2. Creates a `ResetPasswordDTO`.
    3. `UserUseCase::resetPassword` verifies the OTP and updates the password.
    4. Clears the OTP from cache.
  - **Response**: 
    - Success: `{ "status": true, "message": "success" }`
    - Failure: `{ "status": false, "message": "Invalid code" }`

#### 2. Authenticated API Endpoints (Requires `auth:api` Middleware)
These endpoints require a valid API token.

- **POST /api/logout**
  - **Purpose**: Logs out the user and deletes device-specific tokens.
  - **Request**: 
    - Body: `{ "device_token": "token123" }`
  - **Workflow**:
    1. Validates input via `LogoutRequest`.
    2. Creates a `LogoutDTO`.
    3. `AuthUserUseCase::logout` deletes the device record and all tokens for the user.
  - **Response**: 
    - Success: `{ "status": true, "message": "success", "data": true }`

- **POST /api/change_password**
  - **Purpose**: Changes the user’s password.
  - **Request**: 
    - Body: `{ "old_password": "secret123", "new_password": "newsecret123" }`
  - **Workflow**:
    1. Validates input via `ChangePasswordRequest`.
    2. Creates a `ChangePasswordDTO`.
    3. `AuthUserUseCase::changePassword` verifies the old password and updates the new one.
  - **Response**: 
    - Success: `{ "status": true, "message": "success" }`
    - Failure: `{ "status": false, "message": "Incorrect old password" }`

- **POST /api/update_account**
  - **Purpose**: Updates the user’s account details (e.g., name).
  - **Request**: 
    - Body: `{ "name": "John Updated" }`
  - **Workflow**:
    1. Validates input via `UpdateAccountRequest`.
    2. Creates an `UpdateAccountDTO`.
    3. `AuthUserUseCase::updateAccount` updates the user record.
  - **Response**: 
    - Success: `{ "status": true, "message": "success", "data": { "id": 1, "name": "John Updated" } }`

- **POST /api/delete_account**
  - **Purpose**: Deletes the user’s account.
  - **Workflow**:
    1. `AuthUserUseCase::deleteAccount` deletes the authenticated user’s record.
  - **Response**: 
    - Success: `{ "status": true, "message": "success" }`

#### 3. Dashboard API Endpoints (Admin Access)
These endpoints are likely intended for an admin dashboard.

- **POST /dashboard/fetch_users**
  - **Purpose**: Fetches a list of users based on filters.
  - **Request**: `{ "name": "John", "email": "john@example.com" }`
  - **Response**: `{ "status": true, "data": [UserResource collection] }`

- **POST /dashboard/fetch_user_details**
  - **Purpose**: Fetches details of a specific user.
  - **Request**: `{ "user_id": 1 }`
  - **Response**: `{ "status": true, "data": UserResource }`

- **POST /dashboard/create_user**
  - **Purpose**: Creates a new user (admin action).
  - **Request**: `{ "name": "Jane", "email": "jane@example.com", ... }`
  - **Response**: `{ "status": true, "data": UserResource }`

- **POST /dashboard/update_user**
  - **Purpose**: Updates an existing user.
  - **Request**: `{ "user_id": 1, "name": "Jane Updated" }`
  - **Response**: `{ "status": true, "data": UserResource }`

- **POST /dashboard/delete_user**
  - **Purpose**: Deletes a user by ID.
  - **Request**: `{ "user_id": 1 }`
  - **Response**: `{ "status": true, "data": true }`

---

### Key Functions Explanation

#### DTOs (Data Transfer Objects)
- **`RegisterDTO`, `LoginDTO`, etc.**:
  - Purpose: Encapsulates validated request data into structured objects.
  - Key Methods: 
    - `toArray()`: Converts DTO to an array for persistence.
    - `credential()`: Filters and returns email/phone credentials.
    - `UserDevice()`: Returns device-related data.

#### Use Cases
- **`UserUseCase`**:
  - Handles unauthenticated user operations (register, login, password reset).
  - Example: `register(RegisterDTO $dto)` creates a user and associates a device.
- **`AuthUserUseCase`**:
  - Manages authenticated user actions (logout, password change, account updates).
  - Example: `changePassword(ChangePasswordDTO $dto)` verifies the old password and updates it.

#### Repository
- **`UserRepository`**:
  - Abstracts database operations.
  - Key Methods:
    - `login(LoginDTO $dto)`: Authenticates a user.
    - `checkCredential(CheckCredentialDTO $dto)`: Finds a user by email/phone.
    - `logout(LogoutDTO $dto)`: Deletes tokens and device records.
    - `sendWhatsAppMessage($phone, $message)`: Sends OTP via WhatsApp.

---

### Database Tables and Relationships

#### 1. `users` Table
- **Columns**:
  - `id`: Primary key.
  - `name`, `username`, `phone`, `email`, `password`: User details.
  - `id_number`, `id_type`, `id_image`: Identification data.
  - `status`, `is_email_verified`, `is_phone_verified`: Account status flags.
  - `email_verified_at`, `phone_verified_at`: Verification timestamps.
  - `organization_id`, `stage_id`, `location_id`: Foreign keys (nullable).
  - `wallet`, `last_login`, `last_os`: Additional metadata.
- **Purpose**: Stores core user information.

#### 2. `user_devices` Table
- **Columns**:
  - `id`: Primary key.
  - `user_id`: Foreign key to `users(id)` (cascade on delete).
  - `device_id`, `device_type`, `device_token`, `device_os`, `device_os_version`, `device_model`: Device info.
- **Purpose**: Tracks devices associated with users.

#### 3. `password_reset_tokens` Table
- **Columns**:
  - `email`: Primary key.
  - `token`, `created_at`: Token for password reset.
- **Purpose**: Temporary storage for password reset tokens.

#### 4. `sessions` Table
- **Columns**:
  - `id`: Primary key.
  - `user_id`: Foreign key to `users(id)`.
  - `ip_address`, `user_agent`, `payload`, `last_activity`: Session data.
- **Purpose**: Manages user sessions.

#### Relationships
- **One-to-Many**: `users` → `user_devices` (a user can have multiple devices).
- **One-to-Many**: `users` → `sessions` (a user can have multiple sessions).

---

### DDD Structure

#### 1. Domain Layer
- **Location**: `App\Modules\User\Domain`
- **Components**:
  - **`UserDTOInterface`**: Defines the contract for DTOs.
  - **Services**: e.g., `EmailNotification` for sending emails.
- **Purpose**: Contains business logic and domain entities (not fully implemented here; mostly DTOs).

#### 2. Application Layer
- **Location**: `App\Modules\User\Application`
- **Components**:
  - **DTOs**: e.g., `RegisterDTO`, `LoginDTO` for data transfer.
  - **UseCases**: e.g., `UserUseCase`, `AuthUserUseCase` orchestrate business logic.
- **Purpose**: Mediates between the domain and infrastructure, handling application-specific workflows.

#### 3. Infrastructure Layer
- **Location**: `App\Modules\User\Infrastructure`
- **Components**:
  - **Models**: `User`, `UserDevice` for Eloquent ORM.
  - **Repositories**: `UserRepository` for database interactions.
  - **ApiService**: `WhatsAppApiService` for external integrations.
- **Purpose**: Handles persistence, external services, and framework-specific implementations.

#### 4. Presentation Layer
- **Location**: `App\Modules\User\Http`
- **Components**:
  - **Controllers**: `UserController`, `AuthUserController` handle HTTP requests.
  - **Requests**: e.g., `RegisterRequest` for validation.
  - **Resources**: `UserResource` for response formatting.
- **Purpose**: Manages HTTP interactions and API responses.

---

### Conclusion
This module is a well-structured implementation of user management using DDD principles in Laravel. It separates concerns effectively, uses DTOs for data consistency, and integrates external services (WhatsApp) for OTP delivery. The database schema supports user authentication, device tracking, and session management, while the API endpoints provide a comprehensive workflow for both public and authenticated users. Future improvements could include more robust domain entities, additional error handling, and documentation for helper functions like `generateOTP()` or `handelPhone()`.