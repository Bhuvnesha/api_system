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

# Login → Get authentication token
Method: POST

URL: http://localhost/php-auth-api/login.php

Body (raw JSON):
{
"email": "john@example.com",
"password": "123456"
}

# Get Dashboard → Test protected route

Method: GET

URL: http://localhost/php-auth-api/dashboard.php

Headers:
Key: Authorization
Value: Bearer <token_from_login_response>

# Get Profile → View current profile

For GET:

Method: GET

URL: http://localhost/php-auth-api/profile.php

Headers:
Authorization: Bearer <token>


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

# Update Password → Change password

Logout → End session

# Logout

Method: POST

URL: http://localhost/php-auth-api/logout.php

Headers:
Authorization: Bearer <token>
