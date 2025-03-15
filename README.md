# Laravel Todo RESTful API

# Technical Test Backend - Talenavi

## Requirements

-   PHP >= 8.0
-   Composer
-   MySQL
-   Laravel 9.x

## Installation

1. **Clone the repository**

```bash
git clone https://github.com/muqsitharsyad/TodoApp
cd TodoApp
```

2. **Install dependencies**

```bash
composer install
```

3. **Configure environment variables**

```bash
cp .env.example .env
```

Then edit `.env` file to set up your database connection:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=todo_db
DB_USERNAME=username
DB_PASSWORD=password
```

4. **Generate application key**

```bash
php artisan key:generate
```

5. **Run migrations**

```bash
php artisan migrate
```

6. **Start the development server**

```bash
php artisan serve
```

The API will be available at `http://localhost:8000/api/`.

## API Documentation

### Todo Operations

#### Create Todo

-   **Endpoint**: `POST /api/todo`
-   **Headers**:
    -   `Accept: application/json`
    -   `Content-Type: application/json`
-   **Body**:

```json
{
    "title": "Task Title", // string, required
    "assignee": "Assignee Name", // string, optional
    "due_date": "YYYY-MM-DD", // date, required, cannot be in the past
    "time_tracked": 0, // numeric, optional, default 0
    "status": "pending", // enum: pending, open, in_progress, completed, optional, default: pending
    "priority": "high" // enum: low, medium, high, required
}
```

-   **Response (201 Created)**:

```json
{
    "success": true,
    "message": "Todo successfully created",
    "data": {
        "id": 1,
        "title": "Task Title",
        "assignee": "Assignee Name",
        "due_date": "YYYY-MM-DD",
        "time_tracked": 0,
        "status": "pending",
        "priority": "high",
        "created_at": "timestamp",
        "updated_at": "timestamp"
    }
}
```

### Excel Export

-   **Endpoint**: `GET /api/todo/export/excel`
-   **Headers**: `Accept: application/json`
-   **Query Parameters** (all optional):
    -   `title`: String (partial match)
    -   `assignee`: Multiple strings separated by commas (e.g., John,Doe)
    -   `start` & `end`: Date range for due_date (e.g., start=2025-03-01&end=2025-04-30)
    -   `min` & `max`: Range for time_tracked values
    -   `status`: Multiple strings separated by commas (e.g., pending,in_progress)
    -   `priority`: Multiple strings separated by commas (e.g., high,medium)
-   **Response**: Excel file download

### Chart Data

#### Status Summary

-   **Endpoint**: `GET /api/chart?type=status`
-   **Headers**: `Accept: application/json`
-   **Response**:

```json
{
    "status_summary": {
        "pending": x,
        "open": x,
        "in_progress": x,
        "completed": x
    }
}
```

#### Priority Summary

-   **Endpoint**: `GET /api/chart?type=priority`
-   **Headers**: `Accept: application/json`
-   **Response**:

```json
{
    "priority_summary": {
        "low": x,
        "medium": x,
        "high": x
    }
}
```

#### Assignee Summary

-   **Endpoint**: `GET /api/chart?type=assignee`
-   **Headers**: `Accept: application/json`
-   **Response**:

```json
{
    "assignee_summary": {
        "John": {
            "total_todos": x,
            "total_pending_todos": x,
            "total_timetracked_completed_todos": x
        },
        "Doe": {
            "total_todos": x,
            "total_pending_todos": x,
            "total_timetracked_completed_todos": x
        }
    }
}
```
