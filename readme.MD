## Quick Reference

# Common PDO Methods:
prepare() - Prepare SQL statement

bindParam() - Bind parameters

execute() - Execute prepared statement

fetch() - Get single row

fetchAll() - Get all rows

rowCount() - Get number of affected rows

lastInsertId() - Get last inserted ID

## Common Response Codes:

200 - Success

201 - Created

400 - Bad Request

401 - Unauthorized

404 - Not Found

## Manual Testing Sequence:

# Register → Create new user

example:
	Method: POST

	URL: http://localhost/php-auth-api/register.php

	Body (raw JSON):
	{
	"name": "John Doe",
	"email": "john@example.com",
	"password": "123456"
	}

	Response:

	{
	  "message": "User created successfully.",
	  "user_id": 1
	}

# Login → Get authentication token
Method: POST

URL: http://localhost/php-auth-api/login.php

Body (raw JSON):
{
"email": "john@example.com",
"password": "123456"
}

Response:

{
  "message": "Login successful.",
  "token": "eyJ1c2VyX2lkIjoxLCJlbWFpbCI6ImpvaG5AZXhhbXBsZS5jb20iLCJ0aW1lc3RhbXAiOjE2MDAwMDAwMDB9",
  "user": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com"
  }
}

# Get Dashboard → Test protected route

Method: GET

URL: http://localhost/php-auth-api/dashboard.php

Headers:
Key: Authorization
Value: Bearer <token_from_login_response>

Response:

{
  "message": "Welcome to Dashboard!",
  "user": {
    "name": "John Doe",
    "email": "john@example.com",
    "created_at": "2023-01-01 00:00:00"
  },
  "stats": {
    "total_visits": 150,
    "last_login": "2023-12-01 10:30:00",
    "account_status": "Active"
  }
}


# Get Profile → View current profile

For GET:

Method: GET

URL: http://localhost/php-auth-api/profile.php

Headers:
Authorization: Bearer <token>

Response:

{
  "message": "Profile retrieved successfully.",
  "profile": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    "created_at": "2023-01-01 00:00:00"
  }
}


Update Profile → Change user details

For PUT (Update):

Method: PUT

URL: http://localhost/php-auth-api/profile.php

Headers:
Authorization: Bearer <token>
Content-Type: application/json

Body (raw JSON, example to update name and email):
{
"name": "John Smith",
"email": "johnsmith@example.com"
}

Response:

{
  "name": "John Smith",
  "email": "johnsmith@example.com",
  "password": "newpassword123"
}


# Update Password → Change password

Logout → End session

# Logout

Method: POST

URL: http://localhost/php-auth-api/logout.php

Headers:
Authorization: Bearer <token>

Response:

{
  "message": "Logged out successfully."
}
