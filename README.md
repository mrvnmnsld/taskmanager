# 🗂 Laravel Task Manager

A simple and efficient Task Management system built with Laravel. Supports user-authenticated task creation, image uploads, subtasks, smart filtering, and automatic archiving of completed tasks.

##  Features

-  Task creation with image upload
-  Subtask management (add, toggle, delete)
-  Filter, search, and sort tasks
-  Task status updates and archiving
-  Auto-archive completed tasks after 30 days
-  Authenticated user access
-  REST + Blade views combined

## 🛠 Tech Stack

- PHP 8.1 (Laravel 10)
- MySQL
- Blade Templates
- Eloquent ORM
- Tailwind
- jQuery 

## 📌 Endpoints Overview

| Method | Endpoint                  | Description                       |
|--------|---------------------------|-----------------------------------|
| GET    | /tasks                    | List tasks with filters           |
| GET    | /tasks/create             | Create form                       |
| POST   | /tasks                    | Store task                        |
| GET    | /tasks/view/{id}          | View task details                 |
| POST   | /tasks/subtask            | Add a subtask                     |
| POST   | /tasks/subtask/done       | Toggle subtask completion         |
| DELETE | /tasks/subtask            | Delete subtask                    |
| PATCH  | /tasks/update-field       | Update task field (status/publish)|
| POST   | /tasks/{id}/mark-done     | Mark task done                    |
| POST   | /tasks/{id}/reopen        | Reopen a completed task           |
| POST   | /tasks/{id}/archive       | Archive a task                    |

## 🔧 Installation

```bash
git clone https://github.com/your-username/task-manager.git
cd task-manager
composer install
cp .env.example .env
php artisan key:generate
# Configure your .env (DB settings, etc.)
php artisan migrate
php artisan serve
