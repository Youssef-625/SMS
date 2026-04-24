# SMS API Documentation

## Overview

This document provides comprehensive documentation for the SMS (School Management System) API. The API follows RESTful conventions and uses JSON for data exchange.

**Base URL:** `http://your-domain.com/api`

## Authentication

The API uses Laravel Sanctum for authentication. All protected endpoints require a valid Bearer token in the Authorization header.

### Header Format
```
Authorization: Bearer {your_api_token}
```

### Getting Started

1. **Login** to get your authentication token
2. **Include** the token in all subsequent requests
3. **Logout** when done to invalidate the token

---

## Authentication Endpoints

### 1. Login
**POST** `/auth/login`

Authenticate user and return API token.

**Request Body:**
```json
{
  "email": "user@example.com",
  "password": "password123"
}
```

**Response (200):**
```json
{
  "success": true,
  "data": {
    "token": "1|abc123def456...",
    "user": {
      "id": 1,
      "name": "John Doe",
      "email": "user@example.com",
      "role": "admin",
      "email_verified_at": "2024-01-01T12:00:00.000000Z",
      "created_at": "2024-01-01T10:00:00.000000Z",
      "updated_at": "2024-01-01T10:00:00.000000Z"
    }
  }
}
```

**Response (401):**
```json
{
  "success": false,
  "message": "Invalid credentials"
}
```

### 2. Logout
**POST** `/auth/logout`

Invalidate the current authentication token.

**Headers:** `Authorization: Bearer {token}`

**Response (200):**
```json
{
  "success": true,
  "message": "Logged out successfully"
}
```

### 3. Get Current User
**GET** `/auth/me`

Get information about the currently authenticated user.

**Headers:** `Authorization: Bearer {token}`

**Response (200):**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "name": "John Doe",
    "email": "user@example.com",
    "role": "admin",
    "email_verified_at": "2024-01-01T12:00:00.000000Z",
    "created_at": "2024-01-01T10:00:00.000000Z",
    "updated_at": "2024-01-01T10:00:00.000000Z"
  }
}
```

---

## User Management Endpoints

**Note:** All user management endpoints require authentication and admin/super_admin role.

### 1. Get All Users
**GET** `/users`

Retrieve a list of all users.

**Headers:** `Authorization: Bearer {token}`

**Response (200):**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "John Doe",
      "email": "john@example.com",
      "role": "admin",
      "email_verified_at": "2024-01-01T12:00:00.000000Z",
      "created_at": "2024-01-01T10:00:00.000000Z",
      "updated_at": "2024-01-01T10:00:00.000000Z"
    },
    {
      "id": 2,
      "name": "Jane Smith",
      "email": "jane@example.com",
      "role": "teacher",
      "email_verified_at": "2024-01-02T12:00:00.000000Z",
      "created_at": "2024-01-02T10:00:00.000000Z",
      "updated_at": "2024-01-02T10:00:00.000000Z"
    }
  ]
}
```

### 2. Create User
**POST** `/users`

Create a new user account.

**Headers:** `Authorization: Bearer {token}`

**Request Body:**
```json
{
  "name": "New User",
  "email": "newuser@example.com",
  "password": "password123",
  "role": "teacher"
}
```

**Validation Rules:**
- `name`: Required, string, max 255 characters
- `email`: Required, valid email, unique
- `password`: Required, string, min 8 characters
- `role`: Required, must be one of: `admin`, `teacher`, `student`, `parent`, `super_admin`

**Response (201):**
```json
{
  "success": true,
  "data": {
    "id": 3,
    "name": "New User",
    "email": "newuser@example.com",
    "role": "teacher",
    "email_verified_at": null,
    "created_at": "2024-01-03T10:00:00.000000Z",
    "updated_at": "2024-01-03T10:00:00.000000Z"
  }
}
```

**Response (403):**
```json
{
  "success": false,
  "message": "You are not allowed to create this role."
}
```

**Response (422):**
```json
{
  "success": false,
  "message": "The given data was invalid.",
  "errors": {
    "email": ["The email has already been taken."],
    "password": ["The password must be at least 8 characters."]
  }
}
```

### 3. Get Single User
**GET** `/users/{id}`

Retrieve details of a specific user.

**Headers:** `Authorization: Bearer {token}`

**Response (200):**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    "role": "admin",
    "email_verified_at": "2024-01-01T12:00:00.000000Z",
    "created_at": "2024-01-01T10:00:00.000000Z",
    "updated_at": "2024-01-01T10:00:00.000000Z"
  }
}
```

**Response (404):**
```json
{
  "success": false,
  "message": "User not found"
}
```

### 4. Update User
**PUT** `/users/{id}`

Update an existing user's information.

**Headers:** `Authorization: Bearer {token}`

**Request Body:**
```json
{
  "name": "Updated Name",
  "email": "updated@example.com",
  "password": "newpassword123",
  "role": "student"
}
```

**Validation Rules:**
- `name`: Optional, string, max 255 characters
- `email`: Optional, valid email, unique
- `password`: Optional, string, min 8 characters
- `role`: Optional, must be one of: `admin`, `teacher`, `student`, `parent`

**Response (200):**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "name": "Updated Name",
    "email": "updated@example.com",
    "role": "student",
    "email_verified_at": "2024-01-01T12:00:00.000000Z",
    "created_at": "2024-01-01T10:00:00.000000Z",
    "updated_at": "2024-01-03T15:30:00.000000Z"
  }
}
```

### 5. Delete User
**DELETE** `/users/{id}`

Delete a user account.

**Headers:** `Authorization: Bearer {token}`

**Response (200):**
```json
{
  "success": true,
  "message": "User deleted successfully"
}
```

**Response (403):**
```json
{
  "success": false,
  "message": "You cannot delete yourself"
}
```

```json
{
  "success": false,
  "message": "You cannot delete a super admin"
}
```

```json
{
  "success": false,
  "message": "You cannot delete an admin"
}
```

---

## Student Management Endpoints

**Note:** All student management endpoints require authentication and admin/super_admin role.

### 1. Get All Students
**GET** `/students`

Retrieve a list of all students with optional search.

**Headers:** `Authorization: Bearer {token}`

**Query Parameters:**
- `search` (optional): Search by student_id, name, or email

**Response (200):**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "user_id": 5,
      "student_id": "STU001",
      "date_of_birth": "2010-05-15",
      "gender": "male",
      "address": "123 Main St",
      "phone": "+201234567890",
      "enrollment_date": "2024-09-01",
      "created_at": "2024-04-19T17:36:51.000000Z",
      "updated_at": "2024-04-19T17:36:51.000000Z",
      "user": {
        "id": 5,
        "name": "John Doe",
        "email": "john@example.com",
        "role": "student"
      }
    }
  ]
}
```

### 2. Create Student
**POST** `/students`

Create a new student account and profile.

**Headers:** `Authorization: Bearer {token}`

**Request Body:**
```json
{
  "name": "Jane Smith",
  "email": "jane@example.com",
  "password": "password123",
  "student_id": "STU002",
  "date_of_birth": "2010-08-20",
  "gender": "female",
  "address": "456 Oak Ave",
  "phone": "+201234567891",
  "enrollment_date": "2024-09-01"
}
```

**Validation Rules:**
- `name`: Required, string, max 255 characters
- `email`: Required, valid email, unique
- `password`: Required, string, min 8 characters
- `student_id`: Required, string, unique
- `date_of_birth`: Optional, date format
- `gender`: Optional, enum: male, female, other
- `address`: Optional, string
- `phone`: Optional, string
- `enrollment_date`: Optional, date format

**Response (201):**
```json
{
  "success": true,
  "data": {
    "id": 2,
    "user_id": 6,
    "student_id": "STU002",
    "date_of_birth": "2010-08-20",
    "gender": "female",
    "address": "456 Oak Ave",
    "phone": "+201234567891",
    "enrollment_date": "2024-09-01",
    "created_at": "2024-04-19T17:40:00.000000Z",
    "updated_at": "2024-04-19T17:40:00.000000Z"
  }
}
```

### 3. Get Single Student
**GET** `/students/{student}`

Retrieve details of a specific student.

**Headers:** `Authorization: Bearer {token}`

**Response (200):**
```json
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
    "updated_at": "2024-04-19T17:36:51.000000Z",
    "user": {
      "id": 5,
      "name": "John Doe",
      "email": "john@example.com",
      "role": "student"
    }
  }
}
```

**Response (404):**
```json
{
  "success": false,
  "message": "Student not found"
}
```

### 4. Update Student
**PUT** `/students/{student}`

Update an existing student's profile information.

**Headers:** `Authorization: Bearer {token}`

**Request Body:**
```json
{
  "date_of_birth": "2010-05-16",
  "gender": "male",
  "address": "124 Main St",
  "phone": "+201234567891"
}
```

**Response (200):**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "user_id": 5,
    "student_id": "STU001",
    "date_of_birth": "2010-05-16",
    "gender": "male",
    "address": "124 Main St",
    "phone": "+201234567891",
    "enrollment_date": "2024-09-01",
    "created_at": "2024-04-19T17:36:51.000000Z",
    "updated_at": "2024-04-19T17:45:00.000000Z"
  }
}
```

### 5. Delete Student
**DELETE** `/students/{student}`

Delete a student account and profile.

**Headers:** `Authorization: Bearer {token}`

**Response (200):**
```json
{
  "success": true,
  "message": "Student deleted successfully"
}
```

---

## Teacher Management Endpoints

**Note:** All teacher management endpoints require authentication and admin/super_admin role.

### 1. Get All Teachers
**GET** `/teachers`

Retrieve a list of all teachers with optional search.

**Headers:** `Authorization: Bearer {token}`

**Query Parameters:**
- `search` (optional): Search by teacher_id, name, or email

**Response (200):**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "user_id": 3,
      "teacher_id": "TCH001",
      "date_of_birth": "1985-03-10",
      "gender": "male",
      "address": "789 Teacher St",
      "phone": "+201234567892",
      "hire_date": "2020-08-15",
      "qualification": "Master's in Mathematics",
      "subject_specialization": "Mathematics",
      "created_at": "2024-04-19T17:36:51.000000Z",
      "updated_at": "2024-04-19T17:36:51.000000Z",
      "user": {
        "id": 3,
        "name": "Dr. Ahmed Hassan",
        "email": "ahmed@example.com",
        "role": "teacher"
      }
    }
  ]
}
```

### 2. Create Teacher
**POST** `/teachers`

Create a new teacher account and profile.

**Headers:** `Authorization: Bearer {token}`

**Request Body:**
```json
{
  "name": "Sarah Johnson",
  "email": "sarah@example.com",
  "password": "password123",
  "teacher_id": "TCH002",
  "date_of_birth": "1988-07-22",
  "gender": "female",
  "address": "321 Educator Ave",
  "phone": "+201234567893",
  "hire_date": "2021-09-01",
  "qualification": "Bachelor's in English",
  "subject_specialization": "English Literature"
}
```

**Validation Rules:**
- `name`: Required, string, max 255 characters
- `email`: Required, valid email, unique
- `password`: Required, string, min 8 characters
- `teacher_id`: Required, string, unique
- `date_of_birth`: Optional, date format
- `gender`: Optional, enum: male, female, other
- `address`: Optional, string
- `phone`: Optional, string
- `hire_date`: Optional, date format
- `qualification`: Optional, string
- `subject_specialization`: Optional, string

**Response (201):**
```json
{
  "success": true,
  "data": {
    "id": 2,
    "user_id": 4,
    "teacher_id": "TCH002",
    "date_of_birth": "1988-07-22",
    "gender": "female",
    "address": "321 Educator Ave",
    "phone": "+201234567893",
    "hire_date": "2021-09-01",
    "qualification": "Bachelor's in English",
    "subject_specialization": "English Literature",
    "created_at": "2024-04-19T17:40:00.000000Z",
    "updated_at": "2024-04-19T17:40:00.000000Z"
  }
}
```

### 3. Get Single Teacher
**GET** `/teachers/{teacher}`

Retrieve details of a specific teacher.

**Headers:** `Authorization: Bearer {token}`

**Response (200):**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "user_id": 3,
    "teacher_id": "TCH001",
    "date_of_birth": "1985-03-10",
    "gender": "male",
    "address": "789 Teacher St",
    "phone": "+201234567892",
    "hire_date": "2020-08-15",
    "qualification": "Master's in Mathematics",
    "subject_specialization": "Mathematics",
    "created_at": "2024-04-19T17:36:51.000000Z",
    "updated_at": "2024-04-19T17:36:51.000000Z",
    "user": {
      "id": 3,
      "name": "Dr. Ahmed Hassan",
      "email": "ahmed@example.com",
      "role": "teacher"
    }
  }
}
```

### 4. Update Teacher
**PUT** `/teachers/{teacher}`

Update an existing teacher's profile information.

**Headers:** `Authorization: Bearer {token}`

**Request Body:**
```json
{
  "qualification": "PhD in Mathematics",
  "subject_specialization": "Advanced Mathematics"
}
```

**Response (200):**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "user_id": 3,
    "teacher_id": "TCH001",
    "date_of_birth": "1985-03-10",
    "gender": "male",
    "address": "789 Teacher St",
    "phone": "+201234567892",
    "hire_date": "2020-08-15",
    "qualification": "PhD in Mathematics",
    "subject_specialization": "Advanced Mathematics",
    "created_at": "2024-04-19T17:36:51.000000Z",
    "updated_at": "2024-04-19T17:45:00.000000Z"
  }
}
```

### 5. Delete Teacher
**DELETE** `/teachers/{teacher}`

Delete a teacher account and profile.

**Headers:** `Authorization: Bearer {token}`

**Response (200):**
```json
{
  "success": true,
  "message": "Teacher deleted successfully"
}
```

---

## Parent Management Endpoints

**Note:** All parent management endpoints require authentication and admin/super_admin role.

### 1. Get All Parents
**GET** `/parents`

Retrieve a list of all parents with optional search.

**Headers:** `Authorization: Bearer {token}`

**Query Parameters:**
- `search` (optional): Search by parent_id, name, or email

**Response (200):**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "user_id": 7,
      "parent_id": "PAR001",
      "phone": "+201234567894",
      "address": "555 Parent Lane",
      "occupation": "Engineer",
      "created_at": "2024-04-19T17:36:51.000000Z",
      "updated_at": "2024-04-19T17:36:51.000000Z",
      "user": {
        "id": 7,
        "name": "Mohamed Ali",
        "email": "mohamed@example.com",
        "role": "parent"
      }
    }
  ]
}
```

### 2. Create Parent
**POST** `/parents`

Create a new parent account and profile.

**Headers:** `Authorization: Bearer {token}`

**Request Body:**
```json
{
  "name": "Fatima Ahmed",
  "email": "fatima@example.com",
  "password": "password123",
  "parent_id": "PAR002",
  "phone": "+201234567895",
  "address": "666 Family St",
  "occupation": "Doctor"
}
```

**Validation Rules:**
- `name`: Required, string, max 255 characters
- `email`: Required, valid email, unique
- `password`: Required, string, min 8 characters
- `parent_id`: Required, string, unique
- `phone`: Optional, string
- `address`: Optional, string
- `occupation`: Optional, string

**Response (201):**
```json
{
  "success": true,
  "data": {
    "id": 2,
    "user_id": 8,
    "parent_id": "PAR002",
    "phone": "+201234567895",
    "address": "666 Family St",
    "occupation": "Doctor",
    "created_at": "2024-04-19T17:40:00.000000Z",
    "updated_at": "2024-04-19T17:40:00.000000Z"
  }
}
```

### 3. Get Single Parent
**GET** `/parents/{parent}`

Retrieve details of a specific parent.

**Headers:** `Authorization: Bearer {token}`

**Response (200):**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "user_id": 7,
    "parent_id": "PAR001",
    "phone": "+201234567894",
    "address": "555 Parent Lane",
    "occupation": "Engineer",
    "created_at": "2024-04-19T17:36:51.000000Z",
    "updated_at": "2024-04-19T17:36:51.000000Z",
    "user": {
      "id": 7,
      "name": "Mohamed Ali",
      "email": "mohamed@example.com",
      "role": "parent"
    }
  }
}
```

### 4. Update Parent
**PUT** `/parents/{parent}`

Update an existing parent's profile information.

**Headers:** `Authorization: Bearer {token}`

**Request Body:**
```json
{
  "phone": "+201234567896",
  "occupation": "Senior Engineer"
}
```

**Response (200):**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "user_id": 7,
    "parent_id": "PAR001",
    "phone": "+201234567896",
    "address": "555 Parent Lane",
    "occupation": "Senior Engineer",
    "created_at": "2024-04-19T17:36:51.000000Z",
    "updated_at": "2024-04-19T17:45:00.000000Z"
  }
}
```

### 5. Delete Parent
**DELETE** `/parents/{parent}`

Delete a parent account and profile.

**Headers:** `Authorization: Bearer {token}`

**Response (200):**
```json
{
  "success": true,
  "message": "Parent deleted successfully"
}
```

---

## File Upload Endpoints

**Note:** All file upload endpoints require authentication.

### 1. Upload Single File
**POST** `/files/upload`

Upload a single file to the server.

**Headers:** `Authorization: Bearer {token}`

**Request Body:** `multipart/form-data`
- `file`: Required, file (max 10MB)
- Allowed types: jpg, jpeg, png, gif, pdf, doc, docx, xls, xlsx, txt

**Response (200):**
```json
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

**Response (422):**
```json
{
  "success": false,
  "message": "The given data was invalid.",
  "errors": {
    "file": ["The file must be a file of type: jpg, jpeg, png, gif, pdf, doc, docx, xls, xlsx, txt."],
    "file": ["The file may not be greater than 10240 kilobytes."]
  }
}
```

### 2. Upload Multiple Files
**POST** `/files/upload-multiple`

Upload multiple files to the server.

**Headers:** `Authorization: Bearer {token}`

**Request Body:** `multipart/form-data`
- `files[]`: Required, array of files (max 10MB each, max 10 files)
- Allowed types: jpg, jpeg, png, gif, pdf, doc, docx, xls, xlsx, txt

**Response (200):**
```json
{
  "success": true,
  "data": [
    {
      "path": "uploads/2024/04/19/file_abc123.jpg",
      "url": "http://your-domain.com/uploads/2024/04/19/file_abc123.jpg",
      "size": 1024000,
      "mime_type": "image/jpeg",
      "original_name": "profile.jpg"
    },
    {
      "path": "uploads/2024/04/19/file_def456.pdf",
      "url": "http://your-domain.com/uploads/2024/04/19/file_def456.pdf",
      "size": 512000,
      "mime_type": "application/pdf",
      "original_name": "document.pdf"
    }
  ]
}
```

### 3. Delete File
**DELETE** `/files/delete`

Delete a file from the server.

**Headers:** `Authorization: Bearer {token}`

**Request Body:**
```json
{
  "path": "uploads/2024/04/19/file_abc123.jpg"
}
```

**Response (200):**
```json
{
  "success": true,
  "message": "File deleted successfully"
}
```

**Response (404):**
```json
{
  "success": false,
  "message": "File not found"
}
```

---

## Notification Endpoints

**Note:** All notification endpoints require authentication.

### 1. Get All Notifications
**GET** `/notifications`

Retrieve a list of notifications for the authenticated user.

**Headers:** `Authorization: Bearer {token}`

**Response (200):**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "user_id": 1,
      "type": "info",
      "title": "Welcome",
      "message": "Welcome to the School Management System",
      "data": null,
      "link": null,
      "is_read": false,
      "read_at": null,
      "created_at": "2024-04-19T17:38:22.000000Z",
      "updated_at": "2024-04-19T17:38:22.000000Z"
    },
    {
      "id": 2,
      "user_id": 1,
      "type": "alert",
      "title": "New Assignment",
      "message": "You have a new assignment due tomorrow",
      "data": {
        "assignment_id": 5,
        "subject": "Mathematics"
      },
      "link": "/assignments/5",
      "is_read": true,
      "read_at": "2024-04-19T18:00:00.000000Z",
      "created_at": "2024-04-19T17:38:22.000000Z",
      "updated_at": "2024-04-19T18:00:00.000000Z"
    }
  ]
}
```

### 2. Get Unread Count
**GET** `/notifications/unread-count`

Get the count of unread notifications for the authenticated user.

**Headers:** `Authorization: Bearer {token}`

**Response (200):**
```json
{
  "success": true,
  "data": {
    "unread_count": 5
  }
}
```

### 3. Mark All as Read
**POST** `/notifications/mark-all-read`

Mark all notifications as read for the authenticated user.

**Headers:** `Authorization: Bearer {token}`

**Response (200):**
```json
{
  "success": true,
  "message": "All notifications marked as read",
  "data": {
    "marked_count": 5
  }
}
```

### 4. Get Single Notification
**GET** `/notifications/{notification}`

Retrieve details of a specific notification.

**Headers:** `Authorization: Bearer {token}`

**Response (200):**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "user_id": 1,
    "type": "info",
    "title": "Welcome",
    "message": "Welcome to the School Management System",
    "data": null,
    "link": null,
    "is_read": false,
    "read_at": null,
    "created_at": "2024-04-19T17:38:22.000000Z",
    "updated_at": "2024-04-19T17:38:22.000000Z"
  }
}
```

**Response (403):**
```json
{
  "success": false,
  "message": "You are not authorized to access this notification"
}
```

### 5. Mark as Read
**POST** `/notifications/{notification}/mark-read`

Mark a specific notification as read.

**Headers:** `Authorization: Bearer {token}`

**Response (200):**
```json
{
  "success": true,
  "message": "Notification marked as read",
  "data": {
    "id": 1,
    "user_id": 1,
    "type": "info",
    "title": "Welcome",
    "message": "Welcome to the School Management System",
    "data": null,
    "link": null,
    "is_read": true,
    "read_at": "2024-04-19T18:00:00.000000Z",
    "created_at": "2024-04-19T17:38:22.000000Z",
    "updated_at": "2024-04-19T18:00:00.000000Z"
  }
}
```

### 6. Delete Notification
**DELETE** `/notifications/{notification}`

Delete a specific notification.

**Headers:** `Authorization: Bearer {token}`

**Response (200):**
```json
{
  "success": true,
  "message": "Notification deleted successfully"
}
```

**Response (403):**
```json
{
  "success": false,
  "message": "You are not authorized to delete this notification"
}
```

---

## Role-Based Access Control

### User Roles and Permissions

| Role | Can Create | Can Update | Can Delete | Notes |
|------|------------|------------|------------|-------|
| **super_admin** | All roles | All roles | All roles | Full access |
| **admin** | teacher, student, parent | teacher, student, parent | teacher, student, parent | Cannot manage admins or super_admins |
| **teacher** | ❌ | ❌ | ❌ | No user management access |
| **student** | ❌ | ❌ | ❌ | No user management access |
| **parent** | ❌ | ❌ | ❌ | No user management access |

### Special Rules

1. **Self-deletion**: Users cannot delete their own accounts
2. **Super Admin protection**: Only super_admins can delete super_admins
3. **Admin protection**: Only super_admins can delete admins
4. **Role creation**: Admins cannot create admin or super_admin accounts

---

## Error Handling

### Standard Error Response Format
```json
{
  "success": false,
  "message": "Error description",
  "errors": {
    "field": ["Error message 1", "Error message 2"]
  }
}
```

### Common HTTP Status Codes

| Status | Meaning | Description |
|--------|---------|-------------|
| 200 | OK | Request successful |
| 201 | Created | Resource created successfully |
| 401 | Unauthorized | Authentication required or invalid |
| 403 | Forbidden | Insufficient permissions |
| 404 | Not Found | Resource not found |
| 422 | Unprocessable Entity | Validation failed |
| 500 | Internal Server Error | Server error |

---

## Usage Examples

### JavaScript/Fetch API

```javascript
// Login
const login = async (email, password) => {
  const response = await fetch('/api/auth/login', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
    },
    body: JSON.stringify({ email, password })
  });
  
  const data = await response.json();
  if (data.success) {
    localStorage.setItem('token', data.data.token);
    return data.data.user;
  }
  throw new Error(data.message);
};

// Get all users
const getUsers = async () => {
  const token = localStorage.getItem('token');
  const response = await fetch('/api/users', {
    headers: {
      'Authorization': `Bearer ${token}`,
      'Content-Type': 'application/json',
    }
  });
  
  const data = await response.json();
  if (data.success) {
    return data.data;
  }
  throw new Error(data.message);
};

// Create user
const createUser = async (userData) => {
  const token = localStorage.getItem('token');
  const response = await fetch('/api/users', {
    method: 'POST',
    headers: {
      'Authorization': `Bearer ${token}`,
      'Content-Type': 'application/json',
    },
    body: JSON.stringify(userData)
  });
  
  const data = await response.json();
  if (data.success) {
    return data.data;
  }
  throw new Error(data.message);
};
```

### cURL Examples

```bash
# Login
curl -X POST http://your-domain.com/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@example.com","password":"password123"}'

# Get all users
curl -X GET http://your-domain.com/api/users \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"

# Create user
curl -X POST http://your-domain.com/api/users \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -H "Content-Type: application/json" \
  -d '{"name":"John Doe","email":"john@example.com","password":"password123","role":"teacher"}'

# Update user
curl -X PUT http://your-domain.com/api/users/1 \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -H "Content-Type: application/json" \
  -d '{"name":"Updated Name"}'

# Delete user
curl -X DELETE http://your-domain.com/api/users/2 \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

---

## Testing

### Postman Collection

You can import the following collection into Postman for easy testing:

```json
{
  "info": {
    "name": "SMS API",
    "description": "School Management System API"
  },
  "variable": [
    {
      "key": "baseUrl",
      "value": "http://your-domain.com/api"
    },
    {
      "key": "token",
      "value": ""
    }
  ]
}
```

### Environment Variables

- `baseUrl`: Your API base URL
- `token`: Authentication token (set after login)

---

## Support

For any issues or questions regarding the API, please contact the development team.

**Last Updated:** April 2026
