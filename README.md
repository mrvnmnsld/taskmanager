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

| Method | Endpoint                        | Description                         |
|--------|----------------------------------|-------------------------------------|
| GET    | /tasks                           | List all tasks                      |
| GET    | /tasks/create                    | Show the task creation form         |
| POST   | /tasks/store                     | Store a new task                    |
| GET    | /tasks/{id}/view                 | View task details                   |
| POST   | /tasks/update/field              | Update task field (status/publish) |
| POST   | /tasks/{id}/done                 | Mark task as done                   |
| POST   | /tasks/{id}/reopen               | Reopen a completed task             |
| POST   | /tasks/{id}/archive              | Archive a task                      |
| POST   | /tasks/add/subtasks              | Add a subtask                       |
| POST   | /tasks/done/subtasks             | Toggle subtask completion           |
| POST   | /tasks/remove/subtasks           | Delete a subtask                    |
| GET    | /users                           | List users (admin only)             |
| GET    | /users/{user}/edit               | Edit user (admin only)              |
| PUT    | /users/{user}                    | Update user (admin only)            |
| GET    | /profile                         | Edit logged-in user's profile       |
| PATCH  | /profile                         | Update profile                      |
| DELETE | /profile                         | Delete account                      |

## 🔧 Installation

```bash
git clone https://github.com/your-username/task-manager.git
cd task-manager
composer install
cp .env.example .env
php artisan key:generate
# Configure your .env (DB settings, etc.)
php artisan migrate
npm run build
php artisan serve
