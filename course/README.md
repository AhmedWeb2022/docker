# CourseApiService

The `CourseApiService` is a modular Laravel-based application designed to manage courses, lessons, and videos. It provides a robust API for creating, updating, fetching, and deleting course-related data. The service is built with a clean architecture, adhering to domain-driven design principles.

---

## Table of Contents

- [CourseApiService](#courseapiservice)
  - [Table of Contents](#table-of-contents)
  - [Features](#features)
  - [Folder Structure](#folder-structure)
    - [Key Folders](#key-folders)
  - [Installation](#installation)
  - [Usage](#usage)
    - [API Endpoints](#api-endpoints)
    - [Dashboard Endpoints](#dashboard-endpoints)
  - [Modules Overview](#modules-overview)
    - [Base Module](#base-module)
    - [Course Module](#course-module)
  - [Service Explanations](#service-explanations)
    - [Repository](#repository)
    - [DTO (Data Transfer Object)](#dto-data-transfer-object)
    - [UseCase](#usecase)
  - [API Documentation](#api-documentation)
  - [Helper Functions](#helper-functions)
  - [Contributing](#contributing)
  - [License](#license)

---

## Features

- Modular architecture for scalability and maintainability.
- CRUD operations for courses, lessons, and videos.
- Support for translations and media (images, videos).
- Pagination and filtering for large datasets.
- Comprehensive validation and DTO-based data handling.
- OpenAPI documentation for API endpoints.

---

## Folder Structure

The project is organized into modules, each encapsulating a specific domain. Below is an overview of the folder structure:

```
app/
├── Modules/
│   ├── Base/
│   │   ├── Application/
│   │   ├── Domain/
│   │   ├── Http/
│   │   ├── Infrastructure/
│   │   └── Providers/
│   ├── Course/
│   │   ├── Application/
│   │   ├── Domain/
│   │   ├── Http/
│   │   ├── Infrastructure/
│   │   └── UseCases/
│   └── ...
```

### Key Folders

- **Base Module**: Contains shared logic, helpers, and base classes.
- **Course Module**: Manages course-related functionality, including lessons and videos.
- **Http**: Handles API routes, controllers, and requests.
- **Infrastructure**: Contains database models, migrations, and repositories.
- **Application**: Includes DTOs, use cases, and resources.

---

## Installation

1. Clone the repository:
   ```bash
   git clone https://github.com/your-repo/CourseApiService.git
   cd CourseApiService
   ```

2. Install dependencies:
   ```bash
   composer install
   ```

3. Set up the `.env` file:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. Run migrations:
   ```bash
   php artisan migrate
   ```

5. Serve the application:
   ```bash
   php artisan serve
   ```

---

## Usage

### API Endpoints

- **Courses**:
  - `POST /dashboard/fetch_courses`: Fetch a list of courses.
  - `POST /dashboard/create_course`: Create a new course.
  - `POST /dashboard/update_course`: Update an existing course.
  - `POST /dashboard/delete_course`: Delete a course.

- **Lessons**:
  - `POST /dashboard/fetch_lessons`: Fetch a list of lessons.
  - `POST /dashboard/create_lesson`: Create a new lesson.

- **Videos**:
  - `POST /dashboard/fetch_course_videos`: Fetch course videos.
  - `POST /dashboard/create_course_video`: Create a new video.

---

### Dashboard Endpoints

- **Fetch Courses**:
  - **Endpoint**: `POST /dashboard/fetch_courses`
  - **Description**: Retrieves a list of courses with optional filters like `parent_id` or `type`.

- **Fetch Course Details**:
  - **Endpoint**: `POST /dashboard/fetch_course_details`
  - **Description**: Fetches detailed information about a specific course by its ID.

- **Create Course**:
  - **Endpoint**: `POST /dashboard/create_course`
  - **Description**: Creates a new course with translations, media, and metadata.

- **Update Course**:
  - **Endpoint**: `POST /dashboard/update_course`
  - **Description**: Updates an existing course, including its translations and media.

- **Delete Course**:
  - **Endpoint**: `POST /dashboard/delete_course`
  - **Description**: Deletes a course and its associated media.

---

## Modules Overview

### Base Module

- **Purpose**: Provides shared functionality like helpers, base DTOs, and service providers.
- **Key Files**:
  - `Helpers/General.php`: Contains utility functions like slug generation and token creation.
  - `Helpers/File.php`: Handles file uploads and deletions.

### Course Module

- **Purpose**: Manages courses, lessons, and videos.
- **Key Components**:
  - **DTOs**: Data Transfer Objects for structured data handling.
  - **Repositories**: Encapsulate database queries.
  - **Use Cases**: Business logic for handling requests.

---

## Service Explanations

### Repository

- **Purpose**: Handles database interactions, such as querying, creating, updating, and deleting records.
- **Example**: `CourseRepository`
  - Filters courses based on DTO inputs.
  - Uses Eloquent models for database operations.

### DTO (Data Transfer Object)

- **Purpose**: Encapsulates and validates data passed between layers.
- **Example**: `CourseDTO`
  - Maps request data to structured attributes.
  - Provides helper methods like `courseVideo()` to extract video-related data.

### UseCase

- **Purpose**: Implements business logic and orchestrates interactions between repositories and other components.
- **Example**: `CourseUseCase`
  - Fetches courses using `CourseRepository`.
  - Handles course creation and updates, including associated video data.

---

## API Documentation

The API is documented using OpenAPI (Swagger). To view the documentation:

1. Start the application.
2. Navigate to `/api/documentation` in your browser.

---

## Helper Functions

The `Base` module includes several helper functions to simplify common tasks:

- **File Management**:
  - `uploadImage($name, $title)`: Uploads an image.
  - `deleteFile($filePath)`: Deletes a file from storage.

- **String Utilities**:
  - `generateSlug($string)`: Converts a string into a URL-friendly slug.

- **Validation**:
  - `checkCredential($credential)`: Determines if a credential is an email or phone number.

---

## Contributing

Contributions are welcome! Please follow these steps:

1. Fork the repository.
2. Create a new branch:
   ```bash
   git checkout -b feature/your-feature
   ```
3. Commit your changes:
   ```bash
   git commit -m "Add your feature"
   ```
4. Push to your branch:
   ```bash
   git push origin feature/your-feature
   ```
5. Open a pull request.

---

## License

This project is licensed under the MIT License. See the `LICENSE` file for details.
