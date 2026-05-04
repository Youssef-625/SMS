# Phase 3 — Academic Operations Endpoints

This document lists the new Phase 3 endpoints for Attendance, Exams & Grades, Assignments & Submissions, and Online exams.

Base URL: `{{base_url}}` (example: `http://127.0.0.1:8000/api`)

Authentication: Laravel Sanctum. Include header:
```
Authorization: Bearer {token}
```

---

Attendance
- GET /attendances
  - Query: ?student_id=, ?classroom_id=, ?date=
  - Returns paginated attendance records with `student.user` and `classroom` relations.

- POST /attendances
  - Body: { student_id, classroom_id, date, status (present|absent|late|excused), notes }
  - Roles allowed: teacher, admin
  - Returns: created/updated attendance record

- GET /attendances/{id}
  - Returns: single attendance with relations

- PUT /attendances/{id}
  - Update status/notes (teacher/admin)

- DELETE /attendances/{id}

---

Exams & Grades
- GET /exams
  - Query: ?classroom_id=, ?subject_id=
  - Returns: list of exams (with subject & classroom)

- POST /exams
  - Body: { subject_id, classroom_id, name, type, max_score, scheduled_at, duration_minutes, is_online, instructions }
  - Roles: teacher/admin

- GET /exams/{id}
  - Returns exam with `grades` relation

- PUT /exams/{id}
- DELETE /exams/{id}

Grades
- GET /grades
  - Query: ?exam_id=, ?student_id=
  - Returns paginated grades with `student.user` and `exam`.

- POST /grades
  - Body: { exam_id, student_id, score, grade, remarks }
  - Roles: teacher/admin (grader recorded as auth user)

- GET /grades/{id}
- DELETE /grades/{id}

---

Assignments & Submissions
- GET /assignments
  - Query: ?classroom_id=, ?teacher_id=
  - Returns assignments with classroom, subject, teacher

- POST /assignments
  - Body: { classroom_id, subject_id, teacher_id, title, description, assigned_at, due_at, points }
  - Roles: teacher/admin

- GET /assignments/{id}
  - Includes `submissions` list

- POST /assignments/{assignment}/submit
  - Body: { student_id, content, file_path, submitted_at }
  - Role: student (or admin/teacher on behalf)
  - Returns: submission (unique per assignment+student)

Submissions
- GET /submissions
  - Query: ?assignment_id=, ?student_id=
  - Returns paginated submissions

- GET /submissions/{id}

- POST /submissions/{submission}/grade
  - Body: { score, feedback }
  - Roles: teacher/admin (grader and graded_at recorded)

- DELETE /submissions/{id}

---

Online exams
- The `exams` table has `is_online` flag and `scheduled_at`/`duration_minutes` fields to support online exam scheduling.
- Building a full online exam engine (question bank, proctoring, live sessions) is out of scope for this initial Phase 3; endpoints above provide scheduling and grading support to integrate a separate online exam frontend.

---

Notes & next steps
- Roles: controllers check `auth()->user()->role` and allow `teacher`, `admin`, `super_admin` where appropriate. You can tighten with middleware if desired.
- Pagination: index endpoints return Laravel pagination (20 per page by default). Use `?page=` and `?per_page=` where supported.
- Seeders: you can add sample exams/assignments in `database/seeders/` for QA.

If you want, I can:
- Add seeders for sample attendance, exams, assignments and submissions.
- Add feature tests for these endpoints.
- Implement a simple online exam question model and endpoints to create/attempt online exams.

