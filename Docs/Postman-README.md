Simple Postman guide — Teachers / Classrooms / Students

Quick steps

1) Import `Docs/Postman-Collection-Phase2.json` into Postman (or `Docs/Simple-Postman-Collection.json` for a minimal set).
2) Set environment variable `base_url` to your API base (e.g. `http://127.0.0.1:8000/api`).
3) Use the `Auth: Login` request with a valid user (e.g. admin or teacher) to get a token.
   - Copy the returned token into the `token` environment variable in Postman.
4) Use `Teacher: My Classrooms` / `Teacher: My Students` while logged in as a teacher to get that teacher's classrooms and students.
5) Use `Admin: Teacher Classrooms` / `Admin: Teacher Students` as an admin user to fetch any teacher's classrooms/students (replace `:teacherId` path param).

Notes
- Responses use the API standard: { success: boolean, message: string, data: ... }.
- Classroom objects include pivot data for relationships (e.g., `pivot.enrolled_at`, `pivot.role`).
- If you need pagination, call resource index endpoints (e.g., GET /classrooms) with query params `?page=2`.

If you want, I can add a Postman pre-request script to automatically set `{{token}}` after login.

