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

---

# Phase 3 — Academic Operations (Attendance, Exams & Grades, Assignments & Submissions)

This section documents the Phase 3 endpoints that were added to support core academic operations. All Phase 3 endpoints are registered in `routes/api.php` and follow the project's JSON response convention (success, message, data). Authentication: Laravel Sanctum — include `Authorization: Bearer {token}`.

Notes about roles:
- Admin / Super Admin: can manage all resources (create/update/delete/index)
- Teacher: can record attendance for their classrooms, create assignments/exams for their classes, and grade submissions
- Student: can submit assignments and view their own submissions/grades

Default pagination: all index endpoints support `?page=` and many teacher/student lists support `?per_page=` (default 20).

Attendance
- GET /attendances
  - Role: admin/teacher
  - Filters: `?classroom_id=`, `?student_id=`, `?date_from=YYYY-MM-DD`, `?date_to=YYYY-MM-DD`, `?status=present|absent|late|excused`
  - Returns: paginated attendances. Each attendance includes `student` (with `user`) and `classroom`.

- POST /attendances
  - Role: admin or teacher (teacher must be assigned to the classroom)
  - Body (JSON):
    {
      "student_id": 123,
      "classroom_id": 45,
      "date": "2026-05-02",
      "status": "present",
      "notes": "Arrived on time"
    }
  - Behavior: idempotent — API uses updateOrCreate so posting the same student/classroom/date updates existing record.
  - Returns: created/updated attendance object.

- GET /attendances/{id} — show attendance
- PUT /attendances/{id} — update fields (status/notes)
- DELETE /attendances/{id} — remove attendance record

Exams
- GET /exams
  - Filters: `?classroom_id=`, `?subject_id=`, `?is_online=0|1`, `?type=quiz|midterm|final|other`, `?date_from=`, `?date_to=`.
  - Returns: exams with `subject`, `classroom`, and optionally related `schedules` or `creator` (teacher)

- POST /exams
  - Role: admin/teacher
  - Body (JSON):
    {
      "subject_id": 12,
      "classroom_id": 45,
      "name": "Midterm - Term 2",
      "type": "midterm",
      "is_online": true,
      "scheduled_at": "2026-05-15T10:00:00Z",
      "duration_minutes": 60,
      "instructions": "Open book: false"
    }
  - Returns: exam record.

- GET /exams/{id}, PUT /exams/{id}, DELETE /exams/{id}

Grades
- GET /grades
  - Role: admin/teacher
  - Filters: `?exam_id=`, `?student_id=`, `?classroom_id=`. Returns grade records with `exam` and `student.user` relations.

- POST /grades
  - Role: admin/teacher (teacher for that exam/classroom)
  - Body (JSON):
    {
      "exam_id": 5,
      "student_id": 123,
      "score": 85.5,
      "remarks": "Good work"
    }
  - Behavior: create grade or update if exam+student exists (unique constraint on exam_id + student_id).

- GET /grades/{id}, DELETE /grades/{id}

Assignments
- GET /assignments
  - Filters: `?classroom_id=`, `?subject_id=`, `?is_published=0|1`, `?due_before=YYYY-MM-DD`
  - Returns: assignment list with `subject`, `classroom`, and `created_by` (teacher user)

- POST /assignments
  - Role: admin/teacher
  - Body (JSON):
    {
      "title": "Homework 3 - Algebra",
      "description": "Solve problems 1..10",
      "subject_id": 12,
      "classroom_id": 45,
      "due_date": "2026-05-10",
      "max_score": 100,
      "is_published": true
    }
  - Returns: created assignment object.

- GET /assignments/{id}, PUT /assignments/{id}, DELETE /assignments/{id}

Submissions
- POST /assignments/{assignment}/submit
  - Role: authenticated student
  - Content-Type: `multipart/form-data` (files) or JSON (text-only)
  - Fields (multipart or JSON): `text` (string), `files[]` (file uploads)
  - Returns: submission record with `assignment`, `student.user`, `submitted_at`.
  - Note: API accepts multiple files; store locations are returned in response.

- GET /submissions (admin)
  - Returns: all submissions with assignment, student, optionally pagination and filters `?assignment_id=`, `?student_id=`.

- GET /submissions/{id}, DELETE /submissions/{id}

- POST /submissions/{submission}/grade
  - Role: teacher assigned to the classroom/subject
  - Body (JSON):
    {
      "score": 92.5,
      "feedback": "Excellent explanations"
    }
  - Behavior: marks `graded_by` and `graded_at`, stores `score` and `feedback`.

Relations summary (what each Phase 3 resource includes)
- Attendance: includes `student` (with `user`) and `classroom`.
- Exam: includes `subject`, `classroom`, `creator` (teacher user) and flags `is_online`, `duration_minutes`.
- Grade: includes `exam`, `student` (with `user`), and optional `remarks`.
- Assignment: includes `subject`, `classroom`, `created_by` (teacher user), `due_date`, `max_score`.
- Submission: includes `assignment`, `student` (with `user`), `files` array, `score`, `feedback`, `graded_by` and `graded_at`.

Postman / Frontend notes
- Use the project's Postman collection (see `Docs/Postman-Collection-Phase2.json` or `Docs/Simple-Postman-Collection.json`). The login response script will capture `token` into environment — use `{{token}}` for `Authorization: Bearer {{token}}`.
- For file uploads (submitting assignments): set request to `multipart/form-data`, include `files[]` for each file and text fields for `text`.
- To grade a submission, use `POST /submissions/{id}/grade` with JSON `{ score, feedback }` and a teacher token.

Seeding Phase 3 data for QA/staging
1) If you already ran Phase 2 seeding, Phase 3 seeders are included in `DatabaseSeeder` and will run with:
```powershell
php artisan migrate --force
php artisan db:seed --force
```
2) If you face duplicates when re-seeding an existing DB, run a fresh migration during development:
```powershell
php artisan migrate:fresh --seed --force
```

Tips & known limits
- The current Phase 3 implementation provides scaffolding for online exams (`is_online`, `scheduled_at`, `duration_minutes`) but does not include a full exam attempt engine (question bank, attempt tracking, auto-grading). If you need the full online-exam flow, I can implement `Question`, `ExamAttempt`, and `AttemptAnswer` models and controllers next.
- Tests: feature tests for Phase 3 were added under `tests/Feature/Phase3/`. CI runs the full test-suite in GitHub Actions (see `.github/workflows/phpunit.yml`). If running tests locally you may need the `pdo_sqlite` extension enabled for your CLI PHP or configure `phpunit` to use a MySQL test database.

---

If you'd like, I can now:
- export a new Postman collection with example bodies/responses for all Phase 3 endpoints, or
- implement the full online-exam attempt engine (models, controllers, seeders, tests), or
- generate example response samples for each endpoint and embed them in this doc.

Tell me which of the above you'd like next.
