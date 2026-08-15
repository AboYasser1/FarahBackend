# Handoff — Laravel API for Flutter

This document explains how the Flutter team should integrate with the Laravel API in this repository.

## Base
- Repository: https://github.com/<your-org>/<repo>
- Branch for handoff: `api-handoff-to-flutter` (or `develop` if agreed)
- Base API URL (local): `http://localhost:8000/api`

## Auth
- Authentication: Laravel Sanctum tokens
- Login endpoint: `POST /api/login`
  - Body: `{ "email": "...", "password": "..." }`
  - Response: `{ data: { token: "<token>", token_type: "Bearer", user: { id, name, email, role } }}`
- Add token to all protected requests as header:
  - `Authorization: Bearer <token>`

## Email Verification
- Email verification is used; users must verify their email before logging in.
- To resend verification email: `POST /api/email/verification-notification` with `{ "email": "..." }`
- Check `user.email_verified_at` on server side — Flutter should consider `login` failure with 403 and message "Please verify your email before logging in." as indicator the user needs to verify email.

## Endpoints (short list)
- `GET /api/cities` — list cities
- `POST /api/register` — register new user (sends verification email)
- `POST /api/login` — login (requires verified email)
- `POST /api/logout` — logout (auth required)
- `POST /api/password/forgot` — send reset link
- `POST /api/password/reset` — reset password
- `GET /api/profile` — get profile (auth required)
- `PATCH /api/profile` — update profile (auth required)
- `DELETE /api/profile` — delete account (auth required)
- `PATCH /api/password` — change password (auth required)
- `API Resource /api/locations` — locations CRUD (auth required)

## Common responses
- Success example (login):

```json
{
  "icon":"success",
  "title":"Login successful",
  "data":{
    "token":"<token>",
    "token_type":"Bearer",
    "user":{ "id":1, "name":"...", "email":"...", "role":"customer" }
  }
}
```

- Error (email not verified): HTTP 403
```json
{ "icon":"error", "title":"Please verify your email before logging in." }
```

- Validation error: HTTP 422
```json
{ "icon":"error", "title":"<first error>", "errors": { "email": ["..."], ... } }
```

## Postman
- Postman collection: `postman_collection.json` (not included) — ask backend dev to export if needed.

## Notes for Flutter
- Use `token_type` + `token` for Authorization header.
- Treat 401 as authentication failure; 403 may indicate email not verified.
- For files (avatar) upload, use multipart/form-data.

---
Contact backend dev: @your-name
