# User Management Module

This project is a **User Management Module** built with **Laravel**. It provides a comprehensive set of features for user authentication, registration, password management, and account updates. The module is designed to be modular, scalable, and easy to integrate into larger applications. It includes both **public-facing APIs** for user interactions and **dashboard APIs** for administrative user management.

---

## Table of Contents

1. [Features](#features)
2. [Installation](#installation)
3. [API Endpoints](#api-endpoints)
4. [Request and Response Formats](#request-and-response-formats)
5. [DTOs (Data Transfer Objects)](#dtos-data-transfer-objects)
6. [Use Cases](#use-cases)
7. [Repositories](#repositories)
8. [Services](#services)
9. [Notifications](#notifications)
10. [Validation](#validation)
11. [Error Handling](#error-handling)
12. [Contributing](#contributing)
13. [License](#license)

---

## Features

### Public Features
- **User Registration**: Register new users with email, phone, and device information.
- **User Login**: Authenticate users and generate API tokens.
- **Password Management**:
  - Reset password using OTP (via email or WhatsApp).
  - Change password for authenticated users.
- **Account Management**:
  - Update account details (e.g., name).
  - Delete user accounts.
- **OTP-Based Authentication**: Verify users using OTP sent via email or WhatsApp.
- **Device Management**: Track user devices for enhanced security.
- **Email and WhatsApp Notifications**: Send OTP codes via email or WhatsApp.

### Dashboard Features (Admin)
- **Fetch Users**: Retrieve a filtered list of users.
- **Fetch User Details**: Get detailed information about a specific user.
- **Create User**: Create new users (admin-only).
- **Update User**: Update user information (admin-only).
- **Delete User**: Delete user accounts (admin-only).

---

## Installation

1. **Clone the repository**:
   ```bash
   git clone <repository-url>
   cd <repository-directory>
   ```

2. **Install dependencies**:
   ```bash
   composer install
   ```

3. **Set up environment variables**:
   - Copy `.env.example` to `.env`:
     ```bash
     cp .env.example .env
     ```
   - Update the `.env` file with your database credentials, WhatsApp API URL, and other settings.

4. **Run migrations**:
   ```bash
   php artisan migrate
   ```

5. **Run the application**:
   ```bash
   php artisan serve
   ```

---

## API Endpoints

### Public Routes

| Endpoint                | Method | Description                          |
|-------------------------|--------|--------------------------------------|
| `/api/register`         | POST   | Register a new user.                 |
| `/api/login`            | POST   | Log in a user.                       |
| `/api/check_credential` | POST   | Check user credentials and send OTP. |
| `/api/check_code`       | POST   | Verify OTP code.                     |
| `/api/reset_password`   | POST   | Reset user password using OTP.       |

### Authenticated Routes

| Endpoint                | Method | Description                          |
|-------------------------|--------|--------------------------------------|
| `/api/logout`           | POST   | Log out a user.                      |
| `/api/change_password`  | POST   | Change user password.                |
| `/api/update_account`   | POST   | Update user account details.         |
| `/api/delete_account`   | POST   | Delete user account.                 |

### Dashboard Routes (Admin)

| Endpoint                     | Method | Description                          |
|------------------------------|--------|--------------------------------------|
| `/api/dashboard/fetch_users` | POST   | Get filtered list of users           |
| `/api/dashboard/fetch_user_details` | POST | Get detailed user information |
| `/api/dashboard/create_user` | POST   | Create new user (admin)              |
| `/api/dashboard/update_user` | POST   | Update user information              |
| `/api/dashboard/delete_user` | POST   | Delete user account                  |

---

## Request and Response Formats

### Public API Examples

#### Register

**Request**:
```json
{
  "name": "John Doe",
  "username": "johndoe",
  "phone": "1234567890",
  "identify_number": "123456789",
  "email": "john.doe@example.com",
  "password": "password123",
  "device_token": "device_token",
  "device_id": "device_id",
  "device_type": "device_type",
  "device_os": "device_os",
  "device_os_version": "device_os_version"
}
```

**Response**:
```json
{
  "status": true,
  "message": "success",
  "data": {
    "id": 1,
    "name": "John Doe",
    "email": "john.doe@example.com",
    "api_token": "api_token"
  }
}
```

#### Login

**Request**:
```json
{
  "credential": "john.doe@example.com",
  "password": "password123",
  "device_id": "device_id",
  "device_token": "device_token",
  "device_type": "device_type",
  "device_os": "device_os",
  "device_os_version": "device_os_version"
}
```

**Response**:
```json
{
  "status": true,
  "message": "success",
  "data": {
    "id": 1,
    "name": "John Doe",
    "email": "john.doe@example.com",
    "api_token": "api_token"
  }
}
```

### Dashboard API Examples

#### Fetch Users

**Request**:
```json
{
  "name": "John",
  "email": "john@example.com"
}
```

**Response**:
```json
{
  "status": true,
  "message": "success",
  "data": [
    {
      "id": 1,
      "name": "John Doe",
      "email": "john.doe@example.com",
      "phone": "1234567890"
    }
  ]
}
```

#### Create User

**Request**:
```json
{
  "name": "Jane Smith",
  "username": "janesmith",
  "phone": "0987654321",
  "identify_number": "987654321",
  "email": "jane.smith@example.com",
  "password": "securepassword"
}
```

**Response**:
```json
{
  "status": true,
  "message": "success",
  "data": {
    "id": 2,
    "name": "Jane Smith",
    "email": "jane.smith@example.com"
  }
}
```

#### Delete User

**Request**:
```json
{
  "user_id": 2
}
```

**Response**:
```json
{
  "status": true,
  "message": "success"
}
```

---

## DTOs (Data Transfer Objects)

DTOs are used to transfer data between layers of the application. They ensure that data is properly formatted and validated before being processed.

### Public DTOs
- **RegisterDTO**: Handles user registration data.
- **LoginDTO**: Handles user login data.
- **CheckCredentialDTO**: Handles credential checking data.
- **CheckCodeDTO**: Handles OTP verification data.
- **ResetPasswordDTO**: Handles password reset data.
- **LogoutDTO**: Handles logout data.
- **ChangePasswordDTO**: Handles password change data.
- **UpdateAccountDTO**: Handles account update data.

### Dashboard DTOs
- **UserDTO**: Handles user ID and detailed user information.
- **UserFilterDTO**: Handles user filtering parameters (name, email).

---

## Use Cases

### Public Use Cases
- **UserUseCase**: Handles user registration, login, credential checking, OTP verification, and password reset.
- **AuthUserUseCase**: Handles authenticated user operations such as logout, password change, account update, and account deletion.

### Dashboard Use Cases
- **UserUseCase**: Handles admin operations such as fetching users, creating users, updating users, and deleting users.

---

## Repositories

Repositories abstract the data access logic, allowing the application to interact with the database without directly coupling to it.

- **UserRepository**: Handles database operations related to users.
- **WhatsAppApiService**: Handles sending WhatsApp messages for OTP verification.

---

## Services

Services provide additional functionality such as sending notifications.

- **EmailNotification**: Sends email notifications for OTP verification.
- **WhatsAppApiService**: Sends WhatsApp messages for OTP verification.

---

## Notifications

Notifications are used to send messages to users via email or WhatsApp.

- **EmailNotification**: Sends an email with an OTP code.
- **WhatsAppApiService**: Sends a WhatsApp message with an OTP code.

---

## Validation

Validation is handled using Laravel's form request classes. Each request class defines the validation rules and formats the data into a DTO.

### Public Validation
- **RegisterRequest**: Validates registration data.
- **LoginRequest**: Validates login data.
- **CheckCredentialRequest**: Validates credential checking data.
- **CheckCodeRequest**: Validates OTP verification data.
- **ResetPasswordRequest**: Validates password reset data.
- **LogoutRequest**: Validates logout data.
- **ChangePasswordRequest**: Validates password change data.
- **UpdateAccountRequest**: Validates account update data.

### Dashboard Validation
- **CreateUserRequest**: Validates user creation data.
- **FetchUserRequest**: Validates user filtering data.
- **FetchUserDetailsRequest**: Validates user ID for fetching details.
- **UpdateUserRequest**: Validates user update data.
- **UserIdRequest**: Validates user ID for deletion or fetching details.

---

## Error Handling

Errors are handled using a consistent response format. Each use case returns a `DataStatus` object that indicates whether the operation was successful or not.

- **DataSuccess**: Indicates a successful operation.
- **DataFailed**: Indicates a failed operation with an error message.

---

## Contributing

Contributions are welcome! Please follow these steps:

1. Fork the repository.
2. Create a new branch for your feature or bugfix.
3. Commit your changes.
4. Submit a pull request.

---

## License

This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for details.

---

**Happy Coding!** 🚀
