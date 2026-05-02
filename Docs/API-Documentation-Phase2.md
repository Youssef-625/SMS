# SMS API Documentation - Phase 2: Academic Structure

## Overview
This document outlines the API endpoints for Phase 2 of the School Management System (SMS), focusing on academic structure including classrooms, subjects, and schedules.

## Base URL
```
http://127.0.0.1:8000/api
```

## Authentication
All endpoints require authentication using Laravel Sanctum tokens. Include the token in the Authorization header:
```
Authorization: Bearer {token}
```

## Phase 2 Endpoints

### 📚 Classrooms

#### Get All Classrooms
```http
GET /api/classrooms
```

**Query Parameters:**
- `grade_level` (optional) - Filter by grade level
- `academic_year` (optional) - Filter by academic year

**Response:**
```json
{
  "success": true,
  "data": {
    "data": [
      {
        "id": 1,
        "name": "Grade 1A",
        "grade_level": "Grade 1",
        "capacity": 25,
        "academic_year": "2024-2025",
        "description": "Primary Grade 1 Class A",
        "is_active": true,
        "created_at": "2026-04-24T15:53:48.000000Z",
        "updated_at": "2026-04-24T15:53:48.000000Z",
        "students": [],
        "teachers": [],
        "subjects": []
      }
    ],
    "links": {...},
    "meta": {...}
  }
}
```

#### Get Single Classroom
```http
GET /api/classrooms/{id}
```

**Response:** Same as above with full relationships loaded

#### Create Classroom
```http
POST /api/classrooms
Authorization: Bearer {token}
Content-Type: application/json
```

**Request Body:**
```json
{
  "name": "Grade 1C",
  "grade_level": "Grade 1",
  "capacity": 25,
  "academic_year": "2024-2025",
  "description": "Primary Grade 1 Class C",
  "is_active": true
}
```

#### Update Classroom
```http
PUT /api/classrooms/{id}
Authorization: Bearer {token}
Content-Type: application/json
```

**Request Body:** Same as create, all fields optional

#### Delete Classroom
```http
DELETE /api/classrooms/{id}
Authorization: Bearer {token}
```

---

### 📖 Subjects

#### Get All Subjects
```http
GET /api/subjects
```

**Query Parameters:**
- `type` (optional) - Filter by type (core, elective, extracurricular)
- `is_active` (optional) - Filter by active status

**Response:**
```json
{
  "success": true,
  "data": {
    "data": [
      {
        "id": 1,
        "name": "Mathematics",
        "code": "MATH",
        "description": "Mathematics fundamentals and problem solving",
        "credits": 5,
        "type": "core",
        "is_active": true,
        "created_at": "2026-04-24T15:53:48.000000Z",
        "updated_at": "2026-04-24T15:53:48.000000Z",
        "classrooms": [],
        "teachers": []
      }
    ],
    "links": {...},
    "meta": {...}
  }
}
```

#### Get Single Subject
```http
GET /api/subjects/{id}
```

#### Create Subject
```http
POST /api/subjects
Authorization: Bearer {token}
Content-Type: application/json
```

**Request Body:**
```json
{
  "name": "Physics",
  "code": "PHY",
  "description": "Physics fundamentals and experiments",
  "credits": 4,
  "type": "core",
  "is_active": true
}
```

#### Update Subject
```http
PUT /api/subjects/{id}
Authorization: Bearer {token}
Content-Type: application/json
```

#### Delete Subject
```http
DELETE /api/subjects/{id}
Authorization: Bearer {token}
```

---

### ⏰ Schedules

#### Get All Schedules
```http
GET /api/schedules
```

**Query Parameters:**
- `classroom_id` (optional) - Filter by classroom
- `teacher_id` (optional) - Filter by teacher
- `day_of_week` (optional) - Filter by day (monday, tuesday, etc.)
- `academic_year` (optional) - Filter by academic year

**Response:**
```json
{
  "success": true,
  "data": {
    "data": [
      {
        "id": 1,
        "classroom_id": 1,
        "subject_id": 1,
        "teacher_id": 1,
        "day_of_week": "monday",
        "start_time": "08:00",
        "end_time": "09:00",
        "room_number": "101",
        "semester": "first",
        "academic_year": "2024-2025",
        "is_active": true,
        "created_at": "2026-04-24T15:53:48.000000Z",
        "updated_at": "2026-04-24T15:53:48.000000Z",
        "classroom": {
          "id": 1,
          "name": "Grade 1A",
          "grade_level": "Grade 1"
        },
        "subject": {
          "id": 1,
          "name": "Mathematics",
          "code": "MATH"
        },
        "teacher": {
          "id": 1,
          "teacher_id": "T001",
          "user": {
            "name": "Ahmed Hassan",
            "email": "ahmed.hassan@sms.com"
          }
        }
      }
    ],
    "links": {...},
    "meta": {...}
  }
}
```

#### Get Single Schedule
```http
GET /api/schedules/{id}
```

#### Create Schedule
```http
POST /api/schedules
Authorization: Bearer {token}
Content-Type: application/json
```

**Request Body:**
```json
{
  "classroom_id": 1,
  "subject_id": 2,
  "teacher_id": 2,
  "day_of_week": "tuesday",
  "start_time": "10:00",
  "end_time": "11:00",
  "room_number": "102",
  "semester": "first",
  "academic_year": "2024-2025",
  "is_active": true
}
```

#### Update Schedule
```http
PUT /api/schedules/{id}
Authorization: Bearer {token}
Content-Type: application/json
```

#### Delete Schedule
```http
DELETE /api/schedules/{id}
Authorization: Bearer {token}
```

---

## 🔗 Relationships Support

### Classroom with Students
```http
GET /api/classrooms/{id}
```
Returns classroom with:
- `students` - All enrolled students
- `teachers` - Assigned teachers
- `subjects` - Associated subjects

### Subject with Classrooms
```http
GET /api/subjects/{id}
```
Returns subject with:
- `classrooms` - All classrooms teaching this subject
- `teachers` - Teachers teaching this subject

### Schedule with Full Relations
```http
GET /api/schedules/{id}
```
Returns schedule with:
- `classroom` - Classroom details
- `subject` - Subject details  
- `teacher` - Teacher details with user info

---

## 🔗 Classroom Relationship Management

### Student Enrollment Management

#### Enroll Student in Classroom
```http
POST /api/classroom-relationships/enroll-student
Authorization: Bearer {token}
Content-Type: application/json
```

**Request Body:**
```json
{
  "classroom_id": 1,
  "student_id": 1,
  "enrolled_at": "2024-09-01",
  "status": "active"
}
```

**Response:**
```json
{
  "success": true,
  "data": {
    "classroom_id": 1,
    "student_id": 1,
    "enrolled_at": "2024-09-01",
    "status": "active"
  }
}
```

#### Remove Student from Classroom
```http
DELETE /api/classroom-relationships/remove-student
Authorization: Bearer {token}
Content-Type: application/json
```

**Request Body:**
```json
{
  "classroom_id": 1,
  "student_id": 1
}
```

#### Update Student Enrollment Status
```http
PUT /api/classroom-relationships/update-student-status
Authorization: Bearer {token}
Content-Type: application/json
```

**Request Body:**
```json
{
  "classroom_id": 1,
  "student_id": 1,
  "status": "transferred"
}
```

---

### Teacher Assignment Management

#### Assign Teacher to Classroom
```http
POST /api/classroom-relationships/assign-teacher
Authorization: Bearer {token}
Content-Type: application/json
```

**Request Body:**
```json
{
  "classroom_id": 1,
  "teacher_id": 1,
  "role": "homeroom",
  "assigned_at": "2024-09-01"
}
```

**Available Roles:** `homeroom`, `subject_teacher`, `assistant`

#### Remove Teacher from Classroom
```http
DELETE /api/classroom-relationships/remove-teacher
Authorization: Bearer {token}
Content-Type: application/json
```

**Request Body:**
```json
{
  "classroom_id": 1,
  "teacher_id": 1
}
```

#### Update Teacher Role
```http
PUT /api/classroom-relationships/update-teacher-role
Authorization: Bearer {token}
Content-Type: application/json
```

**Request Body:**
```json
{
  "classroom_id": 1,
  "teacher_id": 1,
  "role": "subject_teacher"
}
```

---

### Subject Assignment Management

#### Assign Subject to Classroom
```http
POST /api/classroom-relationships/assign-subject
Authorization: Bearer {token}
Content-Type: application/json
```

**Request Body:**
```json
{
  "classroom_id": 1,
  "subject_id": 1,
  "teacher_id": 1,
  "weekly_hours": 5,
  "semester": "first",
  "academic_year": "2024-2025"
}
```

#### Remove Subject from Classroom
```http
DELETE /api/classroom-relationships/remove-subject
Authorization: Bearer {token}
Content-Type: application/json
```

**Request Body:**
```json
{
  "classroom_id": 1,
  "subject_id": 1
}
```

#### Change Subject Teacher ⭐
```http
PUT /api/classroom-relationships/change-subject-teacher
Authorization: Bearer {token}
Content-Type: application/json
```

**Request Body:**
```json
{
  "classroom_id": 1,
  "subject_id": 1,
  "new_teacher_id": 2
}
```

**Response:**
```json
{
  "success": true,
  "data": {
    "classroom_id": 1,
    "subject_id": 1,
    "teacher_id": 2
  }
}
```

#### Update Subject Weekly Hours
```http
PUT /api/classroom-relationships/update-subject-hours
Authorization: Bearer {token}
Content-Type: application/json
```

**Request Body:**
```json
{
  "classroom_id": 1,
  "subject_id": 1,
  "weekly_hours": 6
}
```

---

### View All Classroom Relationships

#### Get Complete Classroom Data
```http
GET /api/classroom-relationships/classroom/{id}
Authorization: Bearer {token}
```

**Response:**
```json
{
  "success": true,
  "data": {
    "classroom": {
      "id": 1,
      "name": "Grade 1A",
      "grade_level": "Grade 1",
      "capacity": 25,
      "academic_year": "2024-2025"
    },
    "students": [
      {
        "id": 1,
        "student_id": "STU001",
        "user": {
          "name": "John Doe",
          "email": "john.doe@sms.com"
        },
        "pivot": {
          "enrolled_at": "2024-09-01",
          "status": "active"
        }
      }
    ],
    "teachers": [
      {
        "id": 1,
        "teacher_id": "T001",
        "user": {
          "name": "Ahmed Hassan",
          "email": "ahmed.hassan@sms.com"
        },
        "pivot": {
          "role": "homeroom",
          "assigned_at": "2024-09-01"
        }
      }
    ],
    "subjects": [
      {
        "id": 1,
        "name": "Mathematics",
        "code": "MATH",
        "pivot": {
          "teacher_id": 1,
          "weekly_hours": 5,
          "semester": "first"
        }
      }
    ]
  }
}
```

---

## 📱 Mobile & Frontend Integration Notes

### Pagination
All list endpoints use Laravel pagination. Response includes:
- `data.data` - Array of results
- `data.links` - Pagination links
- `data.meta` - Pagination metadata

### Error Handling
All endpoints return consistent error format:
```json
{
  "success": false,
  "message": "Error description",
  "errors": null
}
```

### Status Codes
- `200` - Success
- `201` - Created
- `204` - No Content (delete)
- `401` - Unauthorized
- `403` - Forbidden (admin only)
- `404` - Not Found
- `422` - Validation Error
- `500` - Server Error

### Required Permissions
All Phase 2 endpoints require:
- Authentication token
- Admin or Super Admin role (`role:admin|super_admin`)

---

## 🧪 Test Data

The database has been seeded with sample data:

### Classrooms (6)
- Grade 1A, Grade 1B, Grade 2A, Grade 3A, Grade 4A, Grade 5A
- Academic Year: 2024-2025
- Capacities: 25-32 students

### Subjects (8)
**Core Subjects:**
- Mathematics (MATH) - 5 credits
- English Language (ENG) - 4 credits
- Science (SCI) - 4 credits
- Social Studies (SOC) - 3 credits
- Physical Education (PE) - 2 credits

**Elective Subjects:**
- Art (ART) - 2 credits
- Music (MUS) - 2 credits
- Computer Science (CS) - 3 credits

### Schedules (7)
- Sample schedules for Grade 1A and Grade 2A
- Monday-Thursday classes
- Various subjects and time slots

---

## 🔧 Development Server

The API is running on:
```
http://127.0.0.1:8000
```

For testing and development, use the test credentials from the database seeder output.
