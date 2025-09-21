Below is the updated documentation where all instances of "user" have been replaced with "teacher" to reflect a context where the module manages teachers instead of generic users. The structure and technical details remain unchanged, only the terminology has been adjusted.

---

## API Documentation: Teacher Authentication and Management Module

### Overview
This module handles teacher authentication, registration, password management, and account management functionalities for a Laravel-based application. It follows a **Domain-Driven Design (DDD)** approach with separation of concerns across layers (Application, Domain, Infrastructure). The system uses DTOs (Data Transfer Objects) for data validation and transfer, Laravel Sanctum for API token-based authentication, and integrates WhatsApp messaging for OTP delivery.

---

### Endpoint Workflow

#### 1. Public API Endpoints (Unauthenticated)
These endpoints are accessible without authentication and handle teacher registration, login, and password reset workflows.

- **POST /api/register**
  - **Purpose**: Registers a new teacher.
  - **Request**: 
    - Body: `{ "name": "John Doe", "username": "johndoe", "phone": "1234567890", "identify_number": "ID123", "email": "john@example.com", "password": "secret123", "device_token": "token123", ... }`
  - **Workflow**:
    1. Validates input via `RegisterRequest`.
    2. Creates a `RegisterDTO` from validated data.
    3. `TeacherUseCase::register` creates a new teacher in the database and associates a device (if provided).
    4. Generates an API token and returns a `TeacherResource`.
  - **Response**: 
    - Success: `{ "status": true, "message": "success", "data": { "id": 1, "name": "John Doe", "api_token": "token" } }`
    - Failure: `{ "status": false, "message": "Error message" }`

- **POST /api/login**
  - **Purpose**: Authenticates a teacher and returns an API token.
  - **Request**: 
    - Body: `{ "credential": "john@example.com", "password": "secret123", "device_token": "token123", ... }`
  - **Workflow**:
    1. Validates input via `LoginRequest`.
    2. Creates a `LoginDTO`.
    3. `TeacherUseCase::login` verifies credentials and updates/creates device info.
    4. Returns a `TeacherResource` with an API token.
  - **Response**: Same as `/register`.

- **POST /api/check_credential**
  - **Purpose**: Initiates OTP verification for password reset or credential check for a teacher.
  - **Request**: 
    - Body: `{ "credential": "john@example.com" }`
  - **Workflow**:
    1. Validates input via `CheckCredentialRequest`.
    2. Creates a `CheckCredentialDTO`.
    3. `TeacherUseCase::checkCredential` finds the teacher, generates an OTP, and sends it via email or WhatsApp.
    4. Stores OTP in cache for 5 minutes.
  - **Response**: 
    - Success: `{ "status": true, "message": "success", "data": true }`
    - Failure: `{ "status": false, "message": "Credential not found" }`

- **POST /api/check_code**
  - **Purpose**: Verifies an OTP code for a teacher.
  - **Request**: 
    - Body: `{ "credential": "john@example.com", "code": "123456" }`
  - **Workflow**:
    1. Validates input via `CheckCodeRequest`.
    2. Creates a `CheckCodeDTO`.
    3. `TeacherUseCase::checkCode` compares the provided code with the cached OTP.
  - **Response**: 
    - Success: `{ "status": true, "message": "success", "data": true }`
    - Failure: `{ "status": false, "message": "Incorrect code" }`

- **POST /api/reset_password**
  - **Purpose**: Resets the teacher’s password using a valid OTP.
  - **Request**: 
    - Body: `{ "credential": "john@example.com", "code": "123456", "password": "newsecret123" }`
  - **Workflow**:
    1. Validates input via `ResetPasswordRequest`.
    2. Creates a `ResetPasswordDTO`.
    3. `TeacherUseCase::resetPassword` verifies the OTP and updates the password.
    4. Clears the OTP from cache.
  - **Response**: 
    - Success: `{ "status": true, "message": "success" }`
    - Failure: `{ "status": false, "message": "Invalid code" }`

#### 2. Authenticated API Endpoints (Requires `auth:api` Middleware)
These endpoints require a valid API token.

- **POST /api/logout**
  - **Purpose**: Logs out the teacher and deletes device-specific tokens.
  - **Request**: 
    - Body: `{ "device_token": "token123" }`
  - **Workflow**:
    1. Validates input via `LogoutRequest`.
    2. Creates a `LogoutDTO`.
    3. `AuthTeacherUseCase::logout` deletes the device record and all tokens for the teacher.
  - **Response**: 
    - Success: `{ "status": true, "message": "success", "data": true }`

- **POST /api/change_password**
  - **Purpose**: Changes the teacher’s password.
  - **Request**: 
    - Body: `{ "old_password": "secret123", "new_password": "newsecret123" }`
  - **Workflow**:
    1. Validates input via `ChangePasswordRequest`.
    2. Creates a `ChangePasswordDTO`.
    3. `AuthTeacherUseCase::changePassword` verifies the old password and updates the new one.
  - **Response**: 
    - Success: `{ "status": true, "message": "success" }`
    - Failure: `{ "status": false, "message": "Incorrect old password" }`

- **POST /api/update_account**
  - **Purpose**: Updates the teacher’s account details (e.g., name).
  - **Request**: 
    - Body: `{ "name": "John Updated" }`
  - **Workflow**:
    1. Validates input via `UpdateAccountRequest`.
    2. Creates an `UpdateAccountDTO`.
    3. `AuthTeacherUseCase::updateAccount` updates the teacher record.
  - **Response**: 
    - Success: `{ "status": true, "message": "success", "data": { "id": 1, "name": "John Updated" } }`

- **POST /api/delete_account**
  - **Purpose**: Deletes the teacher’s account.
  - **Workflow**:
    1. `AuthTeacherUseCase::deleteAccount` deletes the authenticated teacher’s record.
  - **Response**: 
    - Success: `{ "status": true, "message": "success" }`

#### 3. Dashboard API Endpoints (Admin Access)
These endpoints are likely intended for an admin dashboard managing teachers.

- **POST /dashboard/fetch_teachers**
  - **Purpose**: Fetches a list of teachers based on filters.
  - **Request**: `{ "name": "John", "email": "john@example.com" }`
  - **Response**: `{ "status": true, "data": [TeacherResource collection] }`

- **POST /dashboard/fetch_teacher_details**
  - **Purpose**: Fetches details of a specific teacher.
  - **Request**: `{ "teacher_id": 1 }`
  - **Response**: `{ "status": true, "data": TeacherResource }`

- **POST /dashboard/create_teacher**
  - **Purpose**: Creates a new teacher (admin action).
  - **Request**: `{ "name": "Jane", "email": "jane@example.com", ... }`
  - **Response**: `{ "status": true, "data": TeacherResource }`

- **POST /dashboard/update_teacher**
  - **Purpose**: Updates an existing teacher.
  - **Request**: `{ "teacher_id": 1, "name": "Jane Updated" }`
  - **Response**: `{ "status": true, "data": TeacherResource }`

- **POST /dashboard/delete_teacher**
  - **Purpose**: Deletes a teacher by ID.
  - **Request**: `{ "teacher_id": 1 }`
  - **Response**: `{ "status": true, "data": true }`

---

### Key Functions Explanation

#### DTOs (Data Transfer Objects)
- **`RegisterDTO`, `LoginDTO`, etc.**:
  - Purpose: Encapsulates validated request data into structured objects for teachers.
  - Key Methods: 
    - `toArray()`: Converts DTO to an array for persistence.
    - `credential()`: Filters and returns email/phone credentials.
    - `TeacherDevice()`: Returns device-related data.

#### Use Cases
- **`TeacherUseCase`**:
  - Handles unauthenticated teacher operations (register, login, password reset).
  - Example: `register(RegisterDTO $dto)` creates a teacher and associates a device.
- **`AuthTeacherUseCase`**:
  - Manages authenticated teacher actions (logout, password change, account updates).
  - Example: `changePassword(ChangePasswordDTO $dto)` verifies the old password and updates it.

#### Repository
- **`TeacherRepository`**:
  - Abstracts database operations for teachers.
  - Key Methods:
    - `login(LoginDTO $dto)`: Authenticates a teacher.
    - `checkCredential(CheckCredentialDTO $dto)`: Finds a teacher by email/phone.
    - `logout(LogoutDTO $dto)`: Deletes tokens and device records.
    - `sendWhatsAppMessage($phone, $message)`: Sends OTP via WhatsApp.

---

### Database Tables and Relationships

#### 1. `teachers` Table
- **Columns**:
  - `id`: Primary key.
  - `name`, `username`, `phone`, `email`, `password`: Teacher details.
  - `id_number`, `id_type`, `id_image`: Identification data.
  - `status`, `is_email_verified`, `is_phone_verified`: Account status flags.
  - `email_verified_at`, `phone_verified_at`: Verification timestamps.
  - `organization_id`, `stage_id`, `location_id`: Foreign keys (nullable).
  - `wallet`, `last_login`, `last_os`: Additional metadata.
- **Purpose**: Stores core teacher information.

#### 2. `teacher_devices` Table
- **Columns**:
  - `id`: Primary key.
  - `teacher_id`: Foreign key to `teachers(id)` (cascade on delete).
  - `device_id`, `device_type`, `device_token`, `device_os`, `device_os_version`, `device_model`: Device info.
- **Purpose**: Tracks devices associated with teachers.

#### 3. `password_reset_tokens` Table
- **Columns**:
  - `email`: Primary key.
  - `token`, `created_at`: Token for password reset.
- **Purpose**: Temporary storage for password reset tokens for teachers.

#### 4. `sessions` Table
- **Columns**:
  - `id`: Primary key.
  - `teacher_id`: Foreign key to `teachers(id)`.
  - `ip_address`, `user_agent`, `payload`, `last_activity`: Session data.
- **Purpose**: Manages teacher sessions.

#### Relationships
- **One-to-Many**: `teachers` → `teacher_devices` (a teacher can have multiple devices).
- **One-to-Many**: `teachers` → `sessions` (a teacher can have multiple sessions).

---

### DDD Structure

#### 1. Domain Layer
- **Location**: `App\Modules\Teacher\Domain`
- **Components**:
  - **`TeacherDTOInterface`**: Defines the contract for DTOs.
  - **Services**: e.g., `EmailNotification` for sending emails.
- **Purpose**: Contains business logic and domain entities (not fully implemented here; mostly DTOs).

#### 2. Application Layer
- **Location**: `App\Modules\Teacher\Application`
- **Components**:
  - **DTOs**: e.g., `RegisterDTO`, `LoginDTO` for data transfer.
  - **UseCases**: e.g., `TeacherUseCase`, `AuthTeacherUseCase` orchestrate business logic.
- **Purpose**: Mediates between the domain and infrastructure, handling application-specific workflows for teachers.

#### 3. Infrastructure Layer
- **Location**: `App\Modules\Teacher\Infrastructure`
- **Components**:
  - **Models**: `Teacher`, `TeacherDevice` for Eloquent ORM.
  - **Repositories**: `TeacherRepository` for database interactions.
  - **ApiService**: `WhatsAppApiService` for external integrations.
- **Purpose**: Handles persistence, external services, and framework-specific implementations for teachers.

#### 4. Presentation Layer
- **Location**: `App\Modules\Teacher\Http`
- **Components**:
  - **Controllers**: `TeacherController`, `AuthTeacherController` handle HTTP requests.
  - **Requests**: e.g., `RegisterRequest` for validation.
  - **Resources**: `TeacherResource` for response formatting.
- **Purpose**: Manages HTTP interactions and API responses for teachers.

---

### Conclusion
This module is a well-structured implementation of teacher management using DDD principles in Laravel. It separates concerns effectively, uses DTOs for data consistency, and integrates external services (WhatsApp) for OTP delivery. The database schema supports teacher authentication, device tracking, and session management, while the API endpoints provide a comprehensive workflow for both public and authenticated teachers. Future improvements could include more robust domain entities, additional error handling, and documentation for helper functions like `generateOTP()` or `handelPhone()`.


