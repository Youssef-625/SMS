# Phase 1 Documentation - Foundation Layer

## Overview

Phase 1 establishes the foundational infrastructure for the School Management System (SMS). This phase focuses on building core features that have no dependencies on other modules, providing a solid base for future development.

**Completion Date:** April 19, 2026

---

## What Was Implemented

### 1. Extended User Profiles

Created specialized profiles for different user roles, expanding beyond the base User model:

- **Student Profile** - Academic and personal information
- **Teacher Profile** - Professional and qualification details
- **Parent Profile** - Contact and family information

### 2. File Upload System

Implemented a robust file handling system for:
- Single file uploads
- Multiple file uploads
- File deletion
- Validation for file types and sizes

### 3. Notification System

Built a comprehensive notification framework for:
- User-specific notifications
- Read/unread status tracking
- Bulk notification management
- Notification categorization

---

## Database Schema Changes

### New Tables

#### Students Table
```sql
CREATE TABLE students (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    student_id VARCHAR(255) UNIQUE NOT NULL,
    date_of_birth DATE NULL,
    gender ENUM('male', 'female', 'other') NULL,
    address VARCHAR(255) NULL,
    phone VARCHAR(255) NULL,
    enrollment_date DATE NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
```

#### Teachers Table
```sql
CREATE TABLE teachers (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    teacher_id VARCHAR(255) UNIQUE NOT NULL,
    date_of_birth DATE NULL,
    gender ENUM('male', 'female', 'other') NULL,
    address VARCHAR(255) NULL,
    phone VARCHAR(255) NULL,
    hire_date DATE NULL,
    qualification VARCHAR(255) NULL,
    subject_specialization VARCHAR(255) NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
```

#### Parents Table
```sql
CREATE TABLE parents (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    parent_id VARCHAR(255) UNIQUE NOT NULL,
    phone VARCHAR(255) NULL,
    address VARCHAR(255) NULL,
    occupation VARCHAR(255) NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
```

#### Notifications Table
```sql
CREATE TABLE notifications (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    type VARCHAR(255) NOT NULL,
    title VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    data JSON NULL,
    link VARCHAR(255) NULL,
    is_read BOOLEAN DEFAULT FALSE,
    read_at TIMESTAMP NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX (user_id, is_read),
    INDEX (created_at)
);
```

---

## New Models

### Student Model
**Location:** `app/Models/Student.php`

**Relationships:**
- BelongsTo User

**Fillable Fields:**
- user_id, student_id, date_of_birth, gender, address, phone, enrollment_date

**Casts:**
- date_of_birth → date
- enrollment_date → date

### Teacher Model
**Location:** `app/Models/Teacher.php`

**Relationships:**
- BelongsTo User

**Fillable Fields:**
- user_id, teacher_id, date_of_birth, gender, address, phone, hire_date, qualification, subject_specialization

**Casts:**
- date_of_birth → date
- hire_date → date

### Parent Model
**Location:** `app/Models/ParentModel.php`

**Relationships:**
- BelongsTo User

**Fillable Fields:**
- user_id, parent_id, phone, address, occupation

### Notification Model
**Location:** `app/Models/Notification.php`

**Relationships:**
- BelongsTo User

**Fillable Fields:**
- user_id, type, title, message, data, link, is_read, read_at

**Casts:**
- data → array
- is_read → boolean
- read_at → datetime

**Methods:**
- `markAsRead()` - Marks notification as read and sets read_at timestamp

### User Model Updates
**Location:** `app/Models/User.php`

**New Relationships:**
- HasOne Student
- HasOne Teacher
- HasOne ParentModel
- HasMany Notification

---

## New Controllers

### StudentController
**Location:** `app/Http/Controllers/StudentController.php`

**Methods:**
- `index(Request)` - List all students with optional search
- `store(Request)` - Create new student (creates User + Student)
- `show(Student)` - Get single student details
- `update(Request, Student)` - Update student profile
- `destroy(Student)` - Delete student (cascades to User)

**Access Control:** Admin/Super Admin only

### TeacherController
**Location:** `app/Http/Controllers/TeacherController.php`

**Methods:**
- `index(Request)` - List all teachers with optional search
- `store(Request)` - Create new teacher (creates User + Teacher)
- `show(Teacher)` - Get single teacher details
- `update(Request, Teacher)` - Update teacher profile
- `destroy(Teacher)` - Delete teacher (cascades to User)

**Access Control:** Admin/Super Admin only

### ParentController
**Location:** `app/Http/Controllers/ParentController.php`

**Methods:**
- `index(Request)` - List all parents with optional search
- `store(Request)` - Create new parent (creates User + Parent)
- `show(ParentModel)` - Get single parent details
- `update(Request, ParentModel)` - Update parent profile
- `destroy(ParentModel)` - Delete parent (cascades to User)

**Access Control:** Admin/Super Admin only

### FileUploadController
**Location:** `app/Http/Controllers/FileUploadController.php`

**Methods:**
- `upload(Request)` - Upload single file
- `uploadMultiple(Request)` - Upload multiple files
- `delete(Request)` - Delete file from storage

**Validation:**
- Max file size: 10MB
- Allowed types: jpg, jpeg, png, gif, pdf, doc, docx, xls, xlsx, txt
- Max files per request: 10

**Storage:** Local storage in `public/uploads/` directory

**Access Control:** All authenticated users

### NotificationController
**Location:** `app/Http/Controllers/NotificationController.php`

**Methods:**
- `index(Request)` - List all notifications for authenticated user
- `show(Notification)` - Get single notification details
- `markAsRead(Notification)` - Mark specific notification as read
- `markAllAsRead(Request)` - Mark all notifications as read
- `unreadCount(Request)` - Get count of unread notifications
- `destroy(Notification)` - Delete notification

**Access Control:** Users can only access their own notifications

---

## New API Routes

### Student Routes
```
GET    /api/students          - List all students
POST   /api/students          - Create student
GET    /api/students/{id}     - Get student details
PUT    /api/students/{id}     - Update student
DELETE /api/students/{id}     - Delete student
```

### Teacher Routes
```
GET    /api/teachers          - List all teachers
POST   /api/teachers          - Create teacher
GET    /api/teachers/{id}     - Get teacher details
PUT    /api/teachers/{id}     - Update teacher
DELETE /api/teachers/{id}     - Delete teacher
```

### Parent Routes
```
GET    /api/parents           - List all parents
POST   /api/parents           - Create parent
GET    /api/parents/{id}      - Get parent details
PUT    /api/parents/{id}      - Update parent
DELETE /api/parents/{id}      - Delete parent
```

### File Upload Routes
```
POST   /api/files/upload             - Upload single file
POST   /api/files/upload-multiple    - Upload multiple files
DELETE /api/files/delete             - Delete file
```

### Notification Routes
```
GET    /api/notifications                    - List notifications
GET    /api/notifications/unread-count       - Get unread count
POST   /api/notifications/mark-all-read      - Mark all as read
GET    /api/notifications/{id}               - Get notification details
POST   /api/notifications/{id}/mark-read     - Mark as read
DELETE /api/notifications/{id}               - Delete notification
```

---

## Role-Based Access Control

### Middleware
The role middleware (`app/Http/Middleware/RoleMiddleware.php`) supports all roles dynamically:
- super_admin
- admin
- teacher
- student
- parent

### Access Summary

| Feature | Super Admin | Admin | Teacher | Student | Parent |
|---------|-------------|-------|---------|---------|--------|
| User Management | All | teacher, student, parent | ❌ | ❌ | ❌ |
| Student Management | ✅ | ✅ | ❌ | ❌ | ❌ |
| Teacher Management | ✅ | ✅ | ❌ | ❌ | ❌ |
| Parent Management | ✅ | ✅ | ❌ | ❌ | ❌ |
| File Upload | ✅ | ✅ | ✅ | ✅ | ✅ |
| Notifications | ✅ | ✅ | ✅ | ✅ | ✅ |

---

## Usage Examples

### Creating a Student

```php
// Request
POST /api/students
Authorization: Bearer {admin_token}

{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "password123",
  "student_id": "STU001",
  "date_of_birth": "2010-05-15",
  "gender": "male",
  "address": "123 Main St",
  "phone": "+201234567890",
  "enrollment_date": "2024-09-01"
}

// Response
{
  "success": true,
  "data": {
    "id": 1,
    "user_id": 5,
    "student_id": "STU001",
    "date_of_birth": "2010-05-15",
    "gender": "male",
    "address": "123 Main St",
    "phone": "+201234567890",
    "enrollment_date": "2024-09-01",
    "created_at": "2024-04-19T17:36:51.000000Z",
    "updated_at": "2024-04-19T17:36:51.000000Z"
  }
}
```

### Uploading a File

```php
// Request
POST /api/files/upload
Authorization: Bearer {token}
Content-Type: multipart/form-data

file: [binary data]

// Response
{
  "success": true,
  "data": {
    "path": "uploads/2024/04/19/file_abc123.jpg",
    "url": "http://your-domain.com/uploads/2024/04/19/file_abc123.jpg",
    "size": 1024000,
    "mime_type": "image/jpeg",
    "original_name": "profile.jpg"
  }
}
```

### Creating a Notification

```php
// In your code
$notification = Notification::create([
    'user_id' => $user->id,
    'type' => 'info',
    'title' => 'Welcome',
    'message' => 'Welcome to the School Management System',
    'data' => ['key' => 'value'],
    'link' => '/dashboard'
]);

// Mark as read
$notification->markAsRead();
```

---

## Migration Commands

To apply the database changes:

```bash
php artisan migrate
```

To rollback if needed:

```bash
php artisan migrate:rollback
```

---

## Testing

### Recommended Testing Approach

1. **Unit Tests** - Test model relationships and validation rules
2. **Feature Tests** - Test API endpoints with various scenarios
3. **Integration Tests** - Test complete workflows (e.g., create student → upload file → send notification)

### Test Data Suggestions

- Create test users for each role
- Test file upload with various file types and sizes
- Test notification creation and read status
- Test search functionality in list endpoints
- Test access control (unauthorized users should be blocked)

---

## Known Limitations

1. **File Storage** - Currently using local storage. For production, consider using cloud storage (S3, Azure Blob, etc.)
2. **Notification Delivery** - Currently only stores notifications. Real-time delivery (WebSocket, Push notifications) not implemented
3. **Search** - Simple search implementation. Advanced filtering and pagination can be added in future phases

---

## Next Steps (Phase 2)

Phase 2 will build upon this foundation to implement:
- Academic Structure (Classes, Grades, Sections)
- Subject Management
- Schedule/Timetable System
- Teacher-Subject Assignments

These features will depend on the user profiles created in Phase 1.

---

## Team Notes

- All profile creation endpoints automatically create both User and profile records
- Cascade delete is enabled: deleting a profile also deletes the associated User
- File uploads are stored in `storage/app/public/uploads` and symlinked to `public/uploads`
- Notifications are user-scoped; users can only access their own notifications
- The role middleware is flexible and can handle any role defined in the system

---

## Documentation References

- **API Documentation:** `Docs/API_DOCUMENTATION.md` - Complete API reference with examples
- **Project Documentation:** `Docs/finalproject_documentation_SMS_forChapter3.pdf` - Overall project requirements
- **System Functions:** `Docs/All_System_Functions.pdf` - Detailed feature list

---

**Phase 1 Status:** ✅ Complete
**Date Completed:** April 19, 2026
