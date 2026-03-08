# RONIN-DASHBOARD-L
A professional administrative dashboard built with **Laravel 9** and **PHP 8.2**.
This project demonstrates full-stack web development skills, including backend logic, database integration, templating with Blade, and responsive frontend design.

It allows administrators to:

- Manage products (create, edit, delete and filter)
- Manage categories (create, edit, delete and filter)
- Manage files (upload, download and delete)
- View and update orders, including order status
- Manage customers and their account statuses
- Search and filter products, orders, and customers
- Monitor dashboard statistics and summaries

## Table of Contents
- [About the Project](#about-the-project)
- [Features](#features)
- [Technologies Used](#technologies-used)
- [Installation](#installation)
- [Usage](#usage)
- [Project Structure](#project-structure)
- [Contributing](#contributing)
- [License](#license)

## About the Project
**RONIN-DASHBOARD-L** is a professional administrative dashboard built with **Laravel 9 and PHP 8.2**.
The project provides a fully-featured admin interface suitable for managing an Ecommerce or business backend.

Key features include:

- Product management (create, edit, delete, filter)
- Category management (create, edit, delete, filter)
- File management (upload, download, delete)
- Customer management, including account status
- Order management with status updates
- Search and filter functionality for products, orders, and customers
- Dashboard statistics and summaries for quick insights
- Optional features: email notifications, responsive design, and modal-based interactions

This project demonstrates **full-stack web development skills**, including backend logic, database integration, templating with Blade, Livewire/AlpineJS (optional) and responsive frontend design.

## Features

The Laravel 9 Dashboard project implements the following core features:

### User Management
- [x] Create account
- [x] Login
- [x] Logout
- [x] Edit profile
- [x] Role management (admin/user)

### Dashboard / Overview
- [x] Main dashboard page
- [x] Display summary widgets
- [x] Recent activity feed
- [x] Charts and graphs (extra)
- [x] Dynamic real-time updates

### Entity Management (CRUD)
- [x] Create records
- [x] Edit records
- [x] Delete records
- [x] View record details
- [x] Pagination of lists
- [x] Search/filter records
- [x] Bulk actions (extra)

### File Management
- [x] Upload files/images
- [x] Validate file types and size
- [x] Store files securely
- [x] Display previews of uploaded files

### Notifications
- [x] Flash messages
- [x] In-app notifications
- [x] Email notifications

### Permissions & Security
- [x] Admin-only sections
- [x] User roles & permissions
- [x] Middleware for route protection
- [x] Audit logs / activity tracking (extra)

### UX/UI
- [x] Responsive layout
- [x] Consistent navigation
- [x] Alerts for success/error

## Technologies Used
This project uses the following technologies and tools:

- **PHP 8.2** – Server-side scripting language powering the application.
- **Laravel 9** – Framework used for building the MVC architecture, routing, controllers, and Blade templates.
- **Blade** – Template engine for rendering dynamic HTML views.
- **Livewire** – Reactive components without writing custom JS
- **MySQL** – Relational database for storing products, categories, orders, files, customers and users.
- **Eloquent ORM** – Object-Relational Mapper for database management.
- **Bootstrap 5** – Frontend framework for responsive and consistent UI design.
- **HTML5 & CSS3** – Markup and styling of the web pages.
- **JavaScript (Vanilla / Optional Alpine.js)** – Client-side interactions and dynamic content.
- **Composer** – Dependency management for PHP packages.
- **Git & GitHub** – Version control and collaboration.

## Installation

1. **Clone the repository**
```bash
git clone https://github.com/username/ronin-dashboard-l.git
cd ronin-dashboard-l
```
2. Install PHP dependencies
```bash
composer install
```
3. Set up environment variables
```bash
cp .env .env.local
# Edit .env.local with your database credentials
```
4. Generate application key
```bash
php artisan key:generate
```
5. Create and migrate the database
```bash
php artisan migrate
```
6. (Optional) Seed the database
```bash
php artisan db:seed
```
7. Install frontend dependencies (if using Vite/Breeze/Jetstream)
```bash
npm install
npm run dev
```
8. Start the Laravel development server
```bash
php artisan serve
```

## Usage
Once the Laravel development server is running, open your browser at: http://127.0.0.1:8000

You can now:
- Log in with an administrator account
- View the main dashboard with summary widgets and statistics
- Manage products: create, edit, delete and filter records
- Manage categories: create, edit, delete and filter records
- Manage files: upload, download and delete
- View and update orders, including changing order status
- Manage customers and their account statuses
- Search and filter products, orders and customers
- Monitor recent activity and system notifications

## Project Structure
```text
ronin-dashboard-l/
├── app/ # Core application code (Controllers, Models, Policies, Services)
│   ├── Console/
│   ├── Events/
│   ├── Exceptions/
│   ├── Http/
│   │   ├── Controllers/
│   │   ├── Middleware/
│   │   └── Requests/
│   ├── Listeners/
│   ├── Models/
│   ├── Notifications/
│   ├── Policies/
│   └── Providers/
├── bootstrap/ # Framework bootstrap and cache
├── config/ # Application configuration files
├── database/
│   ├── factories/ # Model factories for testing
│   ├── migrations/ # Database migrations
│   └── seeders/ # Database seeders
├── lang/
├── public/ # Public web directory (document root)
│   └── index.php # Front controller
├── resources/
│   ├── css/
│   ├── js/
│   └── views/ # Blade templates
├── routes/ # Route definitions (web.php, api.php)
├── storage/ # Logs, compiled templates, uploaded files
├── tests/ # PHPUnit / Feature tests
├── vendor/ # Composer dependencies
├── artisan # Laravel CLI
├── composer.json # PHP dependencies
└── README.md # Project documentation
...
```

## Contributing
Contributions are welcome! To contribute to this project:

1. Fork the repository.
2. Create a new branch for your feature or fix:
```bash
git checkout -b feature/new-feature
```
3. Commit your changes with a descriptive message:
```bash
git commit -m "Add new feature"
```
4. Push your branch to your fork:
```bash
git push origin feature/new-feature
```
5. Open a Pull Request on the main repository and describe your changes.

## License
This project is currently unlicensed. You may view or fork it for demo purposes.
A proper license (e.g., MIT) may be added in the future.
