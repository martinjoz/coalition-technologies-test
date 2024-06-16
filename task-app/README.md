# Drag and Drop Sortable List Application

This is a web application for managing a list of tasks with drag-and-drop functionality for sorting. It allows users to add, edit, delete, and reorder tasks seamlessly.

## Features

-   Drag-and-drop functionality for sorting tasks
-   Add new tasks
-   Edit existing tasks
-   Delete tasks with confirmation
-   Real-time update of task order

## Technologies Used

-   Laravel (PHP Framework)
-   HTML, CSS, JavaScript
-   jQuery
-   Bootstrap
-   Toastr for notifications
-   SweetAlert2 for alert dialogs

## Prerequisites

-   PHP >= 7.3
-   Composer
-   Node.js & npm
-   A web server (e.g., Apache, Nginx)

## Installation

1. **Clone the repository:**

    ```bash
    git clone https://github.com/your-username/drag-and-drop-sortable-list.git
    cd drag-and-drop-sortable-list
    ```

2. **Install PHP dependencies:**

    ```bash
    composer install
    ```

3. **Install JavaScript dependencies:**

    ```bash
    npm install
    ```

4. **Copy the `.env.example` file to `.env` and configure your environment variables:**

    ```bash
    cp .env.example .env
    ```

    Update the `.env` file with your database configuration and other necessary settings.

5. **Generate an application key:**

    ```bash
    php artisan key:generate
    ```

6. **Run database migrations:**

    ```bash
    php artisan migrate
    ```

7. **Build the front-end assets:**

    ```bash
    npm run dev
    ```

## Usage

1. **Start the development server:**

    ```bash
    php artisan serve
    ```

2. **Access the application:**

    Open your web browser and navigate to `http://localhost:8000`.

## Deployment

For deploying to a production environment, follow these steps:

1. **Set up your production environment:**

    Ensure you have a web server like Apache or Nginx configured to serve your Laravel application. Make sure PHP and Composer are installed on your server.

2. **Clone the repository on the server:**

    ```bash
    git clone https://github.com/your-username/drag-and-drop-sortable-list.git
    cd drag-and-drop-sortable-list
    ```

3. **Install PHP dependencies:**

    ```bash
    composer install --optimize-autoloader --no-dev
    ```

4. **Install JavaScript dependencies and build assets:**

    ```bash
    npm install
    npm run production
    ```

5. **Set up the environment variables:**

    ```bash
    cp .env.example .env
    ```

    Update the `.env` file with your production settings.

6. **Generate an application key:**

    ```bash
    php artisan key:generate
    ```

7. **Run database migrations:**

    ```bash
    php artisan migrate --force
    ```

8. **Set the correct permissions:**

    ```bash
    chown -R www-data:www-data /path-to-your-project
    chmod -R 775 /path-to-your-project/storage
    chmod -R 775 /path-to-your-project/bootstrap/cache
    ```

9. **Configure the web server:**

    Configure your web server to serve the Laravel application. For Nginx, you can use the following example configuration:

    ```nginx
    server {
        listen 80;
        server_name your-domain.com;
        root /path-to-your-project/public;

        add_header X-Frame-Options "SAMEORIGIN";
        add_header X-XSS-Protection "1; mode=block";
        add_header X-Content-Type-Options "nosniff";

        index index.php;

        charset utf-8;

        location / {
            try_files $uri $uri/ /index.php?$query_string;
        }

        location = /favicon.ico { access_log off; log_not_found off; }
        location = /robots.txt  { access_log off; log_not_found off; }

        error_page 404 /index.php;

        location ~ \.php$ {
            fastcgi_pass unix:/var/run/php/php7.4-fpm.sock;
            fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
            include fastcgi_params;
            fastcgi_param PATH_INFO $fastcgi_path_info;
        }

        location ~ /\.(?!well-known).* {
            deny all;
        }
    }
    ```

## Contributing

Contributions are welcome! Please open an issue or submit a pull request if you have any improvements or bug fixes.

## License

This project is licensed under the MIT License.
