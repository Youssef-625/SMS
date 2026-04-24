# Authentication - API usage

This project exposes simple token-based authentication endpoints using Laravel Sanctum.

Prerequisites
- PHP and Composer installed
- Project dependencies installed: `composer install`
- Database migrated and seeded (see commands below)

Seed an admin user (local/dev)

Run these commands (PowerShell):

```powershell
# create sqlite file if you use sqlite, optional
if (-Not (Test-Path .\database\database.sqlite)) { New-Item .\database\database.sqlite -ItemType File | Out-Null }

php artisan migrate --force
php artisan db:seed --class=DatabaseSeeder
php artisan serve --host=127.0.0.1 --port=8001
```

By default the seeder creates an admin account with:
- email: `admin@example.com`
- password: `password`
- role: `admin`

Endpoints

- POST /api/auth/login
  - Body (JSON): { "email": "...", "password": "..." }
  - Returns: JSON { success, message, data: { token, user } }

- GET /api/auth/me
  - Header: `Authorization: Bearer <token>`
  - Returns current user data

- GET /api/auth/refresh
  - Header: `Authorization: Bearer <token>`
  - Requires role `admin` (this project uses string roles)
  - Returns full user resource

- POST /api/auth/logout
  - Header: `Authorization: Bearer <token>`
  - Revokes the current access token

Examples

Using curl (Linux/macOS or Windows with curl):

```bash
# Login and capture token (bash)
resp=$(curl -s -X POST "http://127.0.0.1:8001/api/auth/login" \
  -H 'Content-Type: application/json' \
  -d '{"email":"admin@example.com","password":"password"}')
token=$(echo "$resp" | jq -r '.data.token')

# Use token to request /me
curl -H "Authorization: Bearer $token" "http://127.0.0.1:8001/api/auth/me"

# Logout
curl -X POST -H "Authorization: Bearer $token" "http://127.0.0.1:8001/api/auth/logout"
```

Using PowerShell (Windows):

```powershell
# login
$r = Invoke-RestMethod -Uri 'http://127.0.0.1:8001/api/auth/login' -Method Post -ContentType 'application/json' -Body '{"email":"admin@example.com","password":"password"}'
$token = $r.data.token

# /me
Invoke-RestMethod -Uri 'http://127.0.0.1:8001/api/auth/me' -Method Get -Headers @{ Authorization = "Bearer $token" }

# logout
Invoke-RestMethod -Uri 'http://127.0.0.1:8001/api/auth/logout' -Method Post -Headers @{ Authorization = "Bearer $token" }
```

Notes
- This project stores `role` as a string (e.g. `admin`, `student`). If you later migrate to an enum-backed role, update `AuthController` and `RoleMiddleware` accordingly.
- For production use, change seeded credentials and use secure storage for secrets.

Troubleshooting
- 401 Unauthorized when calling protected routes: ensure you pass `Authorization: Bearer <token>` header, run migrations and seeders, and use the correct token returned by `/auth/login`.
- If `/auth/login` fails with validation errors, ensure the request body includes both `email` and `password` fields and they meet the validation rules.

If you want, I can add Feature tests that cover the login → me → refresh → logout flow.

Postman (quick setup for frontend & mobile teams)
-----------------------------------------------

If your frontend or mobile team prefers Postman, you can import the Postman collection provided in the repository (`Postman-Auth-Collection.postman_collection.json`) and the environment file (`Postman-Auth-Env.postman_environment.json`).

Steps:

1. Open Postman and import the collection file `Postman-Auth-Collection.postman_collection.json` (File → Import).
2. Import the environment file `Postman-Auth-Env.postman_environment.json` (File → Import) and select it in the top-right environment dropdown.
3. Make sure `base_url` in the environment points to your running server (e.g. `http://127.0.0.1:8001`).
4. Run the `Login` request. The collection's `Login` request includes a test script that will automatically save the returned token to the environment variable `auth_token`.
5. Run `Me`, `Refresh` or `Logout` requests — they use the `Authorization: Bearer {{auth_token}}` header and will send the saved token.

Notes for Postman use:
- If the `auth_token` environment variable is not set after login, check the response body for errors (validation or credentials). You can manually copy the token into the environment variable.
- The collection uses `{{base_url}}` and `{{auth_token}}` variables to make switching environments (local/staging) easy.

Files added to repo for Postman:
- `Postman-Auth-Collection.postman_collection.json` — a ready-to-import collection with Login, Me, Refresh, and Logout requests; the Login request has a test script that sets `auth_token`.
- `Postman-Auth-Env.postman_environment.json` — an environment file with `base_url` and `auth_token` variables.

