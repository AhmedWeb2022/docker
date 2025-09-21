Below is the updated documentation with all instances of "teacher" replaced with "employee" to reflect a context where the module manages employees instead of teachers. The structure and technical details remain unchanged, only the terminology has been adjusted.

---

## API Documentation: Employee Authentication and Management Module

### Overview
This module handles employee authentication, registration, password management, and account management functionalities for a Laravel-based application. It follows a **Domain-Driven Design (DDD)** approach with separation of concerns across layers (Application, Domain, Infrastructure). The system uses DTOs (Data Transfer Objects) for data validation and transfer, Laravel Sanctum for API token-based authentication, and integrates WhatsApp messaging for OTP delivery.

---

### Endpoint Workflow

#### 1. Public API Endpoints (Unauthenticated)
These endpoints are accessible without authentication and handle employee registration, login, and password reset workflows.

- **POST /api/register**
  - **Purpose**: Registers a new employee.
  - **Request**: 
    - Body: `{ "name": "John Doe", "username": "johndoe", "phone": "1234567890", "identify_number": "ID123", "email": "john@example.com", "password": "secret123", "device_token": "token123", ... }`
  - **Workflow**:
    1. Validates input via `RegisterRequest`.
    2. Creates a `RegisterDTO` from validated data.
    3. `EmployeeUseCase::register` creates a new employee in the database and associates a device (if provided).
    4. Generates an API token and returns an `EmployeeResource`.
  - **Response**: 
    - Success: `{ "status": true, "message": "success", "data": { "id": 1, "name": "John Doe", "api_token": "token" } }`
    - Failure: `{ "status": false, "message": "Error message" }`

- **POST /api/login**
  - **Purpose**: Authenticates an employee and returns an API token.
  - **Request**: 
    - Body: `{ "credential": "john@example.com", "password": "secret123", "device_token": "token123", ... }`
  - **Workflow**:
    1. Validates input via `LoginRequest`.
    2. Creates a `LoginDTO`.
    3. `EmployeeUseCase::login` verifies credentials and updates/creates device info.
    4. Returns an `EmployeeResource` with an API token.
  - **Response**: Same as `/register`.

- **POST /api/check_credential**
  - **Purpose**: Initiates OTP verification for password reset or credential check for an employee.
  - **Request**: 
    - Body: `{ "credential": "john@example.com" }`
  - **Workflow**:
    1. Validates input via `CheckCredentialRequest`.
    2. Creates a `CheckCredentialDTO`.
    3. `EmployeeUseCase::checkCredential` finds the employee, generates an OTP, and sends it via email or WhatsApp.
    4. Stores OTP in cache for 5 minutes.
  - **Response**: 
    - Success: `{ "status": true, "message": "success", "data": true }`
    - Failure: `{ "status": false, "message": "Credential not found" }`

- **POST /api/check_code**
  - **Purpose**: Verifies an OTP code for an employee.
  - **Request**: 
    - Body: `{ "credential": "john@example.com", "code": "123456" }`
  - **Workflow**:
    1. Validates input via `CheckCodeRequest`.
    2. Creates a `CheckCodeDTO`.
    3. `EmployeeUseCase::checkCode` compares the provided code with the cached OTP.
  - **Response**: 
    - Success: `{ "status": true, "message": "success", "data": true }`
    - Failure: `{ "status": false, "message": "Incorrect code" }`

- **POST /api/reset_password**
  - **Purpose**: Resets the employee’s password using a valid OTP.
  - **Request**: 
    - Body: `{ "credential": "john@example.com", "code": "123456", "password": "newsecret123" }`
  - **Workflow**:
    1. Validates input via `ResetPasswordRequest`.
    2. Creates a `ResetPasswordDTO`.
    3. `EmployeeUseCase::resetPassword` verifies the OTP and updates the password.
    4. Clears the OTP from cache.
  - **Response**: 
    - Success: `{ "status": true, "message": "success" }`
    - Failure: `{ "status": false, "message": "Invalid code" }`

#### 2. Authenticated API Endpoints (Requires `auth:api` Middleware)
These endpoints require a valid API token.

- **POST /api/logout**
  - **Purpose**: Logs out the employee and deletes device-specific tokens.
  - **Request**: 
    - Body: `{ "device_token": "token123" }`
  - **Workflow**:
    1. Validates input via `LogoutRequest`.
    2. Creates a `LogoutDTO`.
    3. `AuthEmployeeUseCase::logout` deletes the device record and all tokens for the employee.
  - **Response**: 
    - Success: `{ "status": true, "message": "success", "data": true }`

- **POST /api/change_password**
  - **Purpose**: Changes the employee’s password.
  - **Request**: 
    - Body: `{ "old_password": "secret123", "new_password": "newsecret123" }`
  - **Workflow**:
    1. Validates input via `ChangePasswordRequest`.
    2. Creates a `ChangePasswordDTO`.
    3. `AuthEmployeeUseCase::changePassword` verifies the old password and updates the new one.
  - **Response**: 
    - Success: `{ "status": true, "message": "success" }`
    - Failure: `{ "status": false, "message": "Incorrect old password" }`

- **POST /api/update_account**
  - **Purpose**: Updates the employee’s account details (e.g., name).
  - **Request**: 
    - Body: `{ "name": "John Updated" }`
  - **Workflow**:
    1. Validates input via `UpdateAccountRequest`.
    2. Creates an `UpdateAccountDTO`.
    3. `AuthEmployeeUseCase::updateAccount` updates the employee record.
  - **Response**: 
    - Success: `{ "status": true, "message": "success", "data": { "id": 1, "name": "John Updated" } }`

- **POST /api/delete_account**
  - **Purpose**: Deletes the employee’s account.
  - **Workflow**:
    1. `AuthEmployeeUseCase::deleteAccount` deletes the authenticated employee’s record.
  - **Response**: 
    - Success: `{ "status": true, "message": "success" }`

#### 3. Dashboard API Endpoints (Admin Access)
These endpoints are likely intended for an admin dashboard managing employees.

- **POST /dashboard/fetch_employees**
  - **Purpose**: Fetches a list of employees based on filters.
  - **Request**: `{ "name": "John", "email": "john@example.com" }`
  - **Response**: `{ "status": true, "data": [EmployeeResource collection] }`

- **POST /dashboard/fetch_employee_details**
  - **Purpose**: Fetches details of a specific employee.
  - **Request**: `{ "employee_id": 1 }`
  - **Response**: `{ "status": true, "data": EmployeeResource }`

- **POST /dashboard/create_employee**
  - **Purpose**: Creates a new employee (admin action).
  - **Request**: `{ "name": "Jane", "email": "jane@example.com", ... }`
  - **Response**: `{ "status": true, "data": EmployeeResource }`

- **POST /dashboard/update_employee**
  - **Purpose**: Updates an existing employee.
  - **Request**: `{ "employee_id": 1, "name": "Jane Updated" }`
  - **Response**: `{ "status": true, "data": EmployeeResource }`

- **POST /dashboard/delete_employee**
  - **Purpose**: Deletes an employee by ID.
  - **Request**: `{ "employee_id": 1 }`
  - **Response**: `{ "status": true, "data": true }`

---

### Key Functions Explanation

#### DTOs (Data Transfer Objects)
- **`RegisterDTO`, `LoginDTO`, etc.**:
  - Purpose: Encapsulates validated request data into structured objects for employees.
  - Key Methods: 
    - `toArray()`: Converts DTO to an array for persistence.
    - `credential()`: Filters and returns email/phone credentials.
    - `EmployeeDevice()`: Returns device-related data.

#### Use Cases
- **`EmployeeUseCase`**:
  - Handles unauthenticated employee operations (register, login, password reset).
  - Example: `register(RegisterDTO $dto)` creates an employee and associates a device.
- **`AuthEmployeeUseCase`**:
  - Manages authenticated employee actions (logout, password change, account updates).
  - Example: `changePassword(ChangePasswordDTO $dto)` verifies the old password and updates it.

#### Repository
- **`EmployeeRepository`**:
  - Abstracts database operations for employees.
  - Key Methods:
    - `login(LoginDTO $dto)`: Authenticates an employee.
    - `checkCredential(CheckCredentialDTO $dto)`: Finds an employee by email/phone.
    - `logout(LogoutDTO $dto)`: Deletes tokens and device records.
    - `sendWhatsAppMessage($phone, $message)`: Sends OTP via WhatsApp.

---

### Database Tables and Relationships

#### 1. `employees` Table
- **Columns**:
  - `id`: Primary key.
  - `name`, `username`, `phone`, `email`, `password`: Employee details.
  - `id_number`, `id_type`, `id_image`: Identification data.
  - `status`, `is_email_verified`, `is_phone_verified`: Account status flags.
  - `email_verified_at`, `phone_verified_at`: Verification timestamps.
  - `organization_id`, `stage_id`, `location_id`: Foreign keys (nullable).
  - `wallet`, `last_login`, `last_os`: Additional metadata.
- **Purpose**: Stores core employee information.

#### 2. `employee_devices` Table
- **Columns**:
  - `id`: Primary key.
  - `employee_id`: Foreign key to `employees(id)` (cascade on delete).
  - `device_id`, `device_type`, `device_token`, `device_os`, `device_os_version`, `device_model`: Device info.
- **Purpose**: Tracks devices associated with employees.

#### 3. `password_reset_tokens` Table
- **Columns**:
  - `email`: Primary key.
  - `token`, `created_at`: Token for password reset.
- **Purpose**: Temporary storage for password reset tokens for employees.

#### 4. `sessions` Table
- **Columns**:
  - `id`: Primary key.
  - `employee_id`: Foreign key to `employees(id)`.
  - `ip_address`, `user_agent`, `payload`, `last_activity`: Session data.
- **Purpose**: Manages employee sessions.

#### Relationships
- **One-to-Many**: `employees` → `employee_devices` (an employee can have multiple devices).
- **One-to-Many**: `employees` → `sessions` (an employee can have multiple sessions).

---

### DDD Structure

#### 1. Domain Layer
- **Location**: `App\Modules\Employee\Domain`
- **Components**:
  - **`EmployeeDTOInterface`**: Defines the contract for DTOs.
  - **Services**: e.g., `EmailNotification` for sending emails.
- **Purpose**: Contains business logic and domain entities (not fully implemented here; mostly DTOs).

#### 2. Application Layer
- **Location**: `App\Modules\Employee\Application`
- **Components**:
  - **DTOs**: e.g., `RegisterDTO`, `LoginDTO` for data transfer.
  - **UseCases**: e.g., `EmployeeUseCase`, `AuthEmployeeUseCase` orchestrate business logic.
- **Purpose**: Mediates between the domain and infrastructure, handling application-specific workflows for employees.

#### 3. Infrastructure Layer
- **Location**: `App\Modules\Employee\Infrastructure`
- **Components**:
  - **Models**: `Employee`, `EmployeeDevice` for Eloquent ORM.
  - **Repositories**: `EmployeeRepository` for database interactions.
  - **ApiService**: `WhatsAppApiService` for external integrations.
- **Purpose**: Handles persistence, external services, and framework-specific implementations for employees.

#### 4. Presentation Layer
- **Location**: `App\Modules\Employee\Http`
- **Components**:
  - **Controllers**: `EmployeeController`, `AuthEmployeeController` handle HTTP requests.
  - **Requests**: e.g., `RegisterRequest` for validation.
  - **Resources**: `EmployeeResource` for response formatting.
- **Purpose**: Manages HTTP interactions and API responses for employees.

---

### Conclusion
This module is a well-structured implementation of employee management using DDD principles in Laravel. It separates concerns effectively, uses DTOs for data consistency, and integrates external services (WhatsApp) for OTP delivery. The database schema supports employee authentication, device tracking, and session management, while the API endpoints provide a comprehensive workflow for both public and authenticated employees. Future improvements could include more robust domain entities, additional error handling, and documentation for helper functions like `generateOTP()` or `handelPhone()`.


