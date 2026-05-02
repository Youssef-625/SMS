# Phase 2 Endpoints — Summary & How to use

This file lists all Phase 2 API endpoints, how to call them, whether they include relations, and available search/filter/query parameters. Use this as a quick reference for frontend and mobile teams.

Base URL: `{{base_url}}` (example: `http://127.0.0.1:8000/api`)

Authentication: Laravel Sanctum. Include header:
```
Authorization: Bearer {token}
```

---

Auth
- POST /auth/login
  - Body: { email, password }
  - Returns: token in response.data (use for Authorization)
  - Relation: N/A
  - Search/Filter: N/A

- POST /auth/logout (auth required)
  - Invalidates current token

- GET /auth/me (auth required)
  - Returns current user and role info

---

Indexes (all support pagination via `?page=`)
- GET /users (admin)
  - Relations: user has `student`, `teacher`, `parentModel` via `user.student`, `user.teacher` if present
  - Filters/search: none built-in (you can add `?search=` in future)

- GET /students (admin)
  - Relations included in index: `user` (email/name)
  - Search: `?search=` (searches user.name or user.email and student_id)

- GET /teachers (admin)
  - Relations included in index: `user`
  - Search: `?search=` (searches user.name or user.email and teacher_id)

- GET /parents (admin)
  - Relations: `user`

- GET /classrooms
  - Relations included by default: `students`, `teachers`, `subjects`
  - Filters: `?grade_level=`, `?academic_year=`; pagination `?page=`

- GET /subjects
  - Relations included: `classrooms`, `teachers`
  - Filters: `?type=core|elective|extracurricular`, `?is_active=0|1`

- GET /schedules
  - Relations included: `classroom`, `subject`, `teacher`
  - Filters: `?classroom_id=`, `?teacher_id=`, `?day_of_week=`, `?academic_year=`

---

Single resources
- GET /classrooms/{id}
  - Returns classroom with `students` (each includes `pivot` with enrolled_at/status), `teachers` (with pivot role/assigned_at), and `subjects` (with pivot fields)

- GET /students/{id}
  - Returns student with `user` and `classrooms` (pivot includes enrolled_at/status)

- GET /teachers/{id}
  - Returns teacher with `user`, optionally `classrooms`, `subjects`, `schedules` (depending on endpoint implementation)

- GET /subjects/{id}
  - Returns subject with `classrooms` and `teachers`

- GET /schedules/{id}
  - Returns schedule with `classroom`, `subject`, `teacher` (teacher includes `user`)

---

Teacher-specific endpoints
- GET /teachers/{teacher}/classrooms (admin)
  - Returns the classrooms assigned to that teacher
  - Relations: Each classroom includes `students.user`

- GET /teachers/{teacher}/students (admin)
  - Returns unique students across that teacher's classrooms
  - Pagination: supports `?per_page=` (default 20)
  - Relations: students include `user`

- GET /teachers/me/classrooms (auth: teacher)
  - Same as above but for the logged-in teacher

- GET /teachers/me/students (auth: teacher)
  - Same as above but for logged-in teacher; supports `?per_page=` (default 20)

Notes: the students endpoints return paginated results to avoid large payloads. Use `?per_page=50` to increase page size.

---

Classroom relationship management (admin)
- POST /classroom-relationships/enroll-student
  - Body: { classroom_id, student_id, enrolled_at, status }
  - Returns: created pivot data

- DELETE /classroom-relationships/remove-student
  - Body: { classroom_id, student_id }

- PUT /classroom-relationships/update-student-status
  - Body: { classroom_id, student_id, status }

- POST /classroom-relationships/assign-teacher
  - Body: { classroom_id, teacher_id, role, assigned_at }

- DELETE /classroom-relationships/remove-teacher
  - Body: { classroom_id, teacher_id }

- PUT /classroom-relationships/update-teacher-role
  - Body: { classroom_id, teacher_id, role }

- POST /classroom-relationships/assign-subject
  - Body: { classroom_id, subject_id, teacher_id, weekly_hours, semester, academic_year }

- DELETE /classroom-relationships/remove-subject
  - Body: { classroom_id, subject_id }

- PUT /classroom-relationships/change-subject-teacher
  - Body: { classroom_id, subject_id, new_teacher_id }

- PUT /classroom-relationships/update-subject-hours
  - Body: { classroom_id, subject_id, weekly_hours }

- GET /classroom-relationships/classroom/{id}
  - Returns full classroom relationships: classroom, students (with user and pivot), teachers (with user and pivot), subjects (with pivot)

---

Files & Notifications (auth)
- POST /files/upload
- POST /files/upload-multiple
- DELETE /files/delete

- GET /notifications
- GET /notifications/unread-count
- POST /notifications/mark-all-read
- GET /notifications/{notification}
- POST /notifications/{notification}/mark-read
- DELETE /notifications/{notification}

---

How to seed Phase 2 for QA / staging
1) Run migrations and seeders (local or staging):
```powershell
php artisan migrate --force
php artisan db:seed --force
```
2) DatabaseSeeder runs Phase 1 and Phase 2 seeders and prints test credentials:
- Super Admin: superadmin@sms.com / password
- Admin: admin@sms.com / password
- Teacher: ahmed.hassan@sms.com / password
- Student: john.doe@sms.com / password

If you want custom sample data, edit the seeders in `database/seeders/`.

---

Common query params summary
- `?page=` — pagination page (all index endpoints)
- `?per_page=` — page size for teacher students endpoints (defaults to 20)
- `?search=` — supported by students/teachers index (searches user.name/user.email and id fields)
- resource-specific filters: see each endpoint above (classrooms: grade_level, academic_year; subjects: type, is_active; schedules: classroom_id, teacher_id, day_of_week, academic_year)

---

If anything is missing or you want this exported as a downloadable Postman collection with examples, tell me and I will produce it.

