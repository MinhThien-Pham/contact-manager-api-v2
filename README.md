# Contact Manager API v2 (PHP / LAMP)

This project is a refactored and improved version of the backend API originally developed for a UCF COP4331 team project.  
The API provides user authentication and full CRUD operations for contacts using a PHP/LAMP stack and JSON-based endpoints.

The original team project repository is here:  
https://github.com/JacksonCammack-UCF/COP4331-Team10-Project1  
(My contributions focused on the LAMP API in the `LAMPAPI` folder, including login, registration, and contact operations.)

This repository represents a personal v2 refactor that focuses on better structure, security, and maintainability.

## Key Improvements in v2

Compared to the original team implementation, this version:

- Extracts shared helpers for:
  - Database connection (`getDbConnection`)
  - Reading JSON input (`getRequestJson`)
  - Sending JSON responses (`sendJson`, `sendError`)
- Centralizes configuration in `config/config.php` instead of duplicating database credentials in each file.
- Uses prepared statements for all database queries.
- Validates input fields and returns consistent JSON error messages with appropriate HTTP status codes.
- Implements password hashing with `password_hash` and `password_verify` for user passwords.
- Sets the database connection charset to `utf8mb4` for safer text handling.
- Fixes logic issues in search queries, including correct operator precedence and user scoping.
- Builds JSON responses using arrays and `json_encode` instead of manual string concatenation.

## Technology Stack

- Language: PHP
- Database: MySQL (or compatible)
- Environment: LAMP stack (Linux, Apache, MySQL, PHP)
- Data format: JSON for both requests and responses
- Documentation: Swagger/OpenAPI (YAML file stored in `docs/swagger.yaml`)

## Project Structure

```text
project-root/
  config/
    config.php           # Database configuration for local use
  src/
    helpers.php          # Shared utilities: DB connection, JSON helpers, error handling
    AddContact.php       # POST: add a new contact
    DeleteContact.php    # POST: delete a contact by ID
    Login.php            # POST: user login
    Register.php         # POST: user registration (with hashed passwords)
    SearchContacts.php   # POST: search contacts for a user
    UpdateContact.php    # POST: update contact details
  docs/
    swagger.yaml         # OpenAPI/Swagger documentation for the API
  README.md              # Project description and usage
