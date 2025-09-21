Below is a detailed documentation for the provided PHP Laravel application code, focusing on the Course Management Module. This documentation covers the endpoint workflows, explains key functions, describes database tables and their relationships, and outlines how the Domain-Driven Design (DDD) structure is implemented.

---

## API Documentation: Course Management Module

### Overview
This module manages courses, lessons, and videos within a Laravel-based application. It follows a **Domain-Driven Design (DDD)** approach, with separation of concerns across layers (Application, Domain, Infrastructure). The system uses DTOs (Data Transfer Objects) for data validation and transfer, supports multilingual content via translations, and leverages Laravel's Eloquent ORM for database interactions. Courses can have associated videos (either links or files), lessons, and hierarchical relationships (parent-child).

---

### Endpoint Workflow

#### 1. Course Endpoints (Dashboard Access)
These endpoints are intended for admin dashboard use and manage course-related operations.

- **POST /dashboard/fetch_courses**
  - **Purpose**: Fetches a list of courses with optional filters.
  - **Request**: 
    - Body: `{ "title": "Math", "type": "1", "parent_id": 1 }`
  - **Workflow**:
    1. Validates input via `FetchCourseRequest`.
    2. Creates a `CourseFilterDTO`.
    3. `CourseUseCase::fetchCourses` filters courses using `CourseRepository`.
    4. Returns either `CourseResource` or `FullCourseResource` based on the `$withChild` parameter.
  - **Response**: 
    - Success: `{ "status": true, "message": "Courses fetched successfully", "data": [CourseResource collection] }`
    - Failure: `{ "status": false, "message": "Error message" }`

- **POST /dashboard/fetch_course_details**
  - **Purpose**: Fetches details of a specific course.
  - **Request**: 
    - Body: `{ "course_id": 1 }`
  - **Workflow**:
    1. Validates input via `CourseIdRequest`.
    2. Creates a `CourseFilterDTO`.
    3. `CourseUseCase::fetchCourseDetails` retrieves the course by ID.
  - **Response**: 
    - Success: `{ "status": true, "message": "Course fetched successfully", "data": CourseResource }`

- **POST /dashboard/create_course**
  - **Purpose**: Creates a new course with an optional video.
  - **Request**: 
    - Body: `{ "translations": {"title_en": "Math", "title_ar": "رياضيات"}, "type": 1, "start_date": "2025-01-01", "end_date": "2025-06-30", "video": {"video_type": "link", "link": "https://example.com/video"} }`
  - **Workflow**:
    1. Validates input via `CreateCourseRequest`.
    2. Creates a `CourseDTO`.
    3. `CourseUseCase::createCourse` creates the course and an associated video (if provided).
  - **Response**: 
    - Success: `{ "status": true, "message": "Course created successfully", "data": CourseResource }`

- **POST /dashboard/update_course**
  - **Purpose**: Updates an existing course and its video (if applicable).
  - **Request**: 
    - Body: `{ "course_id": 1, "translations": {"title_en": "Advanced Math"}, "status": "active" }`
  - **Workflow**:
    1. Validates input via `UpdateCourseRequest`.
    2. Creates a `CourseDTO`.
    3. `CourseUseCase::updateCourse` updates the course and its video.
  - **Response**: 
    - Success: `{ "status": true, "message": "Course updated successfully", "data": CourseResource }`

- **POST /dashboard/delete_course**
  - **Purpose**: Deletes a course and its associated video.
  - **Request**: 
    - Body: `{ "course_id": 1 }`
  - **Workflow**:
    1. Validates input via `CourseIdRequest`.
    2. Creates a `CourseFilterDTO`.
    3. `CourseUseCase::deleteCourse` deletes the course and its video.
  - **Response**: 
    - Success: `{ "status": true, "message": "Course deleted successfully" }`

#### 2. Lesson Endpoints (Dashboard Access)
These endpoints manage lessons within courses.

- **POST /dashboard/fetch_lessons**
  - **Purpose**: Fetches a list of lessons with optional filters.
  - **Request**: 
    - Body: `{ "course_id": 1, "type": 1 }`
  - **Workflow**:
    1. Validates input via `FetchLessonRequest`.
    2. Creates a `LessonFilterDTO`.
    3. `LessonUseCase::fetchLessons` filters lessons.
  - **Response**: 
    - Success: `{ "status": true, "message": "Courses fetched successfully", "data": [LessonResource collection] }`

- **POST /dashboard/fetch_lesson_details**
  - **Purpose**: Fetches details of a specific lesson.
  - **Request**: 
    - Body: `{ "lesson_id": 1 }`
  - **Workflow**:
    1. Validates input via `LessonIdRequest`.
    2. Creates a `LessonFilterDTO`.
    3. `LessonUseCase::fetchLessonDetails` retrieves the lesson by ID.
  - **Response**: 
    - Success: `{ "status": true, "message": "Course Lesson fetched successfully", "data": LessonResource }`

- **POST /dashboard/create_lesson**
  - **Purpose**: Creates a new lesson.
  - **Request**: 
    - Body: `{ "course_id": 1, "translations": {"title_en": "Intro"}, "type": 1 }`
  - **Workflow**:
    1. Validates input via `CreateLessonRequest`.
    2. Creates a `LessonDTO`.
    3. `LessonUseCase::createLesson` creates the lesson.
  - **Response**: 
    - Success: `{ "status": true, "message": "Course Lesson created successfully", "data": LessonResource }`

- **POST /dashboard/update_lesson**
  - **Purpose**: Updates an existing lesson.
  - **Request**: 
    - Body: `{ "lesson_id": 1, "translations": {"title_en": "Intro Updated"} }`
  - **Workflow**:
    1. Validates input via `UpdateLessonRequest`.
    2. Creates a `LessonDTO`.
    3. `LessonUseCase::updateLesson` updates the lesson.
  - **Response**: 
    - Success: `{ "status": true, "message": "Course Lesson updated successfully", "data": LessonResource }`

- **POST /dashboard/delete_lesson**
  - **Purpose**: Deletes a lesson.
  - **Request**: 
    - Body: `{ "lesson_id": 1 }`
  - **Workflow**:
    1. Validates input via `LessonIdRequest`.
    2. Creates a `LessonFilterDTO`.
    3. `LessonUseCase::deleteLesson` deletes the lesson.
  - **Response**: 
    - Success: `{ "status": true, "message": "Course Lesson deleted successfully" }`

#### 3. Video Endpoints (Dashboard Access)
These endpoints manage videos associated with courses or lessons.

- **POST /dashboard/fetch_videos**
  - **Purpose**: Fetches a list of videos with filters.
  - **Request**: 
    - Body: `{ "course_id": 1 }`
  - **Workflow**:
    1. Validates input via `FetchVideoRequest`.
    2. Creates a `VideoFilterDTO`.
    3. `VideoUseCase::fetchVideos` filters videos.
  - **Response**: 
    - Success: `{ "status": true, "message": "Courses fetched successfully", "data": [VideoResource collection] }`

- **POST /dashboard/fetch_video_details**
  - **Purpose**: Fetches details of a specific video.
  - **Request**: 
    - Body: `{ "video_id": 1 }`
  - **Workflow**:
    1. Validates input via `VideoIdRequest`.
    2. Creates a `VideoFilterDTO`.
    3. `VideoUseCase::fetchVideoDetails` retrieves the video by ID.
  - **Response**: 
    - Success: `{ "status": true, "message": "Course Video fetched successfully", "data": VideoResource }`

- **POST /dashboard/create_video**
  - **Purpose**: Creates a new video.
  - **Request**: 
    - Body: `{ "course_id": 1, "type": 0, "video": "https://example.com/video" }`
  - **Workflow**:
    1. Validates input via `CreateVideoRequest`.
    2. Creates a `VideoDTO`.
    3. `VideoUseCase::createVideo` creates the video.
  - **Response**: 
    - Success: `{ "status": true, "message": "Course Video created successfully", "data": VideoResource }`

- **POST /dashboard/update_video**
  - **Purpose**: Updates an existing video.
  - **Request**: 
    - Body: `{ "course_video_id": 1, "video": "https://newexample.com/video" }`
  - **Workflow**:
    1. Validates input via `UpdateVideoRequest`.
    2. Creates a `VideoDTO`.
    3. `VideoUseCase::updateVideo` updates the video.
  - **Response**: 
    - Success: `{ "status": true, "message": "Course Video updated successfully", "data": VideoResource }`

- **POST /dashboard/delete_video**
  - **Purpose**: Deletes a video.
  - **Request**: 
    - Body: `{ "video_id": 1 }`
  - **Workflow**:
    1. Validates input via `VideoIdRequest`.
    2. Creates a `VideoFilterDTO`.
    3. `VideoUseCase::deleteVideo` deletes the video.
  - **Response**: 
    - Success: `{ "status": true, "message": "Course Video deleted successfully" }`

---

### Key Functions Explanation

#### DTOs (Data Transfer Objects)
- **`CourseDTO`**:
  - Purpose: Encapsulates course data for creation/update.
  - Key Methods: 
    - `courseVideo()`: Returns video-related data for the course.
    - `excludedAttributes()`: Lists attributes to exclude from persistence (e.g., `file`, `video_type`).
- **`CourseFilterDTO`**:
  - Purpose: Filters courses based on criteria like title or type.
  - Key Methods: `courseVideo()` returns video-related filter data.
- **`LessonDTO` & `LessonFilterDTO`**:
  - Purpose: Manages lesson data and filtering.
- **`VideoDTO` & `VideoFilterDTO`**:
  - Purpose: Manages video data and filtering.

#### Use Cases
- **`CourseUseCase`**:
  - Handles course operations (fetch, create, update, delete).
  - Example: `createCourse(CourseDTO $dto)` creates a course and its video.
- **`LessonUseCase`**:
  - Manages lesson operations.
  - Example: `fetchLessons(LessonFilterDTO $dto)` retrieves filtered lessons.
- **`VideoUseCase`**:
  - Manages video operations.
  - Example: `updateVideo(VideoDTO $dto)` updates video details.

#### Repositories
- **`CourseRepository`**:
  - Abstracts course database operations.
  - Key Methods: `filter()` applies dynamic filters based on DTO properties.
- **`LessonRepository`**:
  - Handles lesson database interactions.
- **`VideoRepository`**:
  - Manages video persistence.

#### Enums
- **`CourseStatusEnum`**: Defines course states (INACTIVE=0, ACTIVE=1).
- **`LessonStatusEnum`**: Defines lesson states (INACTIVE=0, ACTIVE=1).
- **`VideoTypeEnum`**: Defines video types (LINK=0, FILE=1).

---

### Database Tables and Relationships

#### 1. `courses` Table
- **Columns**:
  - `id`: Primary key.
  - `parent_id`: Foreign key to `courses(id)` (nullable).
  - `organization_id`, `stage_id`, `subject_id`: Foreign keys (nullable).
  - `type`: Course type (1=online, 2=offline, 3=both).
  - `status`: 0=inactive, 1=active.
  - `is_private`, `has_website`, `has_app`: Boolean flags.
  - `start_date`, `end_date`: Date range.
  - `image`: Image path.
- **Purpose**: Stores course metadata.

#### 2. `course_translations` Table
- **Columns**:
  - `id`: Primary key.
  - `course_id`: Foreign key to `courses(id)` (cascade on delete).
  - `locale`: Language code (e.g., "en", "ar").
  - `title`: Localized course title.
- **Purpose**: Stores multilingual course titles.

#### 3. `lessons` Table
- **Columns**:
  - `id`: Primary key.
  - `parent_id`: Foreign key to `lessons(id)` (nullable).
  - `course_id`: Foreign key to `courses(id)` (cascade on delete).
  - `organization_id`: Foreign key (nullable).
  - `is_free`, `is_standalone`: Boolean flags.
  - `type`: Lesson type (1=online, 2=offline, 3=both).
  - `status`: 0=inactive, 1=active.
  - `price`: Lesson cost.
  - `image`: Image path.
- **Purpose**: Stores lesson metadata.

#### 4. `lesson_translations` Table
- **Columns**:
  - `id`: Primary key.
  - `lesson_id`: Foreign key to `lessons(id)` (cascade on delete).
  - `locale`: Language code.
  - `title`: Localized lesson title.
- **Purpose**: Stores multilingual lesson titles.

#### 5. `videos` Table
- **Columns**:
  - `id`: Primary key.
  - `videoable_id`, `videoable_type`: Polymorphic relationship fields.
  - `title`: Video title (nullable).
  - `link`, `file`: Video source (link or file path).
  - `video_type`: Type of video (e.g., "youtube").
  - `is_file`: 0=link, 1=file.
- **Purpose**: Stores video metadata for courses or lessons.

#### Relationships
- **One-to-Many**: `courses` → `course_translations` (multilingual titles).
- **One-to-Many**: `courses` → `lessons` (a course has multiple lessons).
- **One-to-One**: `courses` → `videos` (morphOne relationship).
- **Self-Referential**: `courses` → `courses` (parent-child hierarchy via `parent_id`).
- **One-to-Many**: `lessons` → `lesson_translations`.
- **Self-Referential**: `lessons` → `lessons` (parent-child hierarchy).

---

### DDD Structure

#### 1. Domain Layer
- **Location**: `App\Modules\Course\Application\Enums`
- **Components**:
  - **`CourseStatusEnum`, `LessonStatusEnum`, `VideoTypeEnum`**: Define business rules and constants.
- **Purpose**: Encapsulates core business logic (minimal here; mostly enums).

#### 2. Application Layer
- **Location**: `App\Modules\Course\Application`
- **Components**:
  - **DTOs**: e.g., `CourseDTO`, `LessonDTO`, `VideoDTO` for data transfer.
  - **UseCases**: e.g., `CourseUseCase`, `LessonUseCase`, `VideoUseCase` orchestrate operations.
- **Purpose**: Mediates between domain and infrastructure, handling workflows.

#### 3. Infrastructure Layer
- **Location**: `App\Modules\Course\Infrastructure`
- **Components**:
  - **Models**: `Course`, `Lesson`, `Video` for Eloquent ORM.
  - **Repositories**: `CourseRepository`, `LessonRepository`, `VideoRepository` for database interactions.
- **Purpose**: Manages persistence and framework-specific implementations.

#### 4. Presentation Layer
- **Location**: `App\Modules\Course\Http`
- **Components**:
  - **Controllers**: `CourseController`, `LessonController`, `VideoController` handle HTTP requests.
  - **Requests**: e.g., `CreateCourseRequest` for validation.
  - **Resources**: `CourseResource`, `LessonResource`, `VideoResource` for response formatting.
- **Purpose**: Manages API interactions and responses.

---

### Conclusion
This module provides a robust implementation of course management using DDD principles in Laravel. It supports hierarchical courses, multilingual content, and video integration (links or files). The database schema is well-designed for scalability, with clear relationships and separation of concerns. Future improvements could include adding more domain logic (e.g., validation in the Domain layer), enhancing error handling, and documenting helper functions like `getTranslation()`.
