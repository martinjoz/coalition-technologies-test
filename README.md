# Drag and Drop Sortable List Application

This is a web application for managing a list of tasks with drag-and-drop functionality for sorting. It allows users to add, edit, delete, and reorder tasks seamlessly.

## Features

- Drag-and-drop functionality for sorting tasks
- Add new tasks
- Edit existing tasks
- Delete tasks with confirmation
- Real-time update of task order

## Technologies Used

- Laravel (PHP Framework)
- HTML, CSS, JavaScript
- jQuery
- Bootstrap
- Toastr for notifications
- SweetAlert2 for alert dialogs

## Prerequisites

- PHP >= 7.3
- Composer
- Node.js & npm
- A web server (e.g., Apache, Nginx)

## Installation

1. **Clone the repository:**

   ```bash
   git clone https://github.com/martinjoz/coalition-technologies-test.git
   cd task-app
   ```

2. **Install PHP dependencies:**

   ```bash
   composer install
   ```

3. **Copy the `.env.example` file to `.env` and configure your environment variables:**

   ```bash
   cp .env.example .env
   ```

   Update the `.env` file with your database configuration and other necessary settings.

4. **Generate an application key:**

   ```bash
   php artisan key:generate
   ```

5. **Run database migrations:**

   ```bash
   php artisan migrate
   ```

## Usage

1. **Start the development server:**

   ```bash
   php artisan serve
   ```

2. **Access the application:**

   Open your web browser and navigate to `http://localhost:8000`.
