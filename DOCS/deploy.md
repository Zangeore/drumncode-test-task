
# Laravel 12 Project Setup with Sail (Local Environment)

This guide assumes you are working on a Unix-like system and have Docker installed.

## 📦 Prerequisites

- Docker
- Docker Compose (v2+ is included with Docker Desktop)
- Git
- PHP 8.3+ (only if you want to run certain scripts without Sail)

## 🚀 Getting Started

1. **Clone the Repository**

   ```bash
   git clone https://github.com/Zangeore/drumncode-test-task.git
   cd drumncode-test-task
   ```

2. **Copy the `.env` file**

   ```bash
   cp .env.example .env
   ```

   Make any necessary adjustments (e.g., `APP_NAME`, `APP_PORT`, DB credentials if needed).

3. **Start Sail**

   If Sail is not yet installed, run:

   ```bash
   ./vendor/bin/sail up
   ```

   If `vendor` directory doesn’t exist yet, first install dependencies:

   ```bash
   docker run --rm \
       -u "$(id -u):$(id -g)" \
       -v $(pwd):/var/www/html \
       -w /var/www/html \
       laravelsail/php83-composer:latest \
       composer install
   ```

   Then run Sail:

   ```bash
   ./vendor/bin/sail up -d
   ```

4. **Generate Application Key**

   ```bash
   ./vendor/bin/sail artisan key:generate
   ```

5. **Run Migrations (Optional)**

   ```bash
   ./vendor/bin/sail artisan migrate
   ```

6. **Access the Application**

   Open [http://localhost](http://localhost) or the port defined in your `.env` (`APP_PORT`).


7. **Generate user via Artisan Command**

   If you need to create a user, you can use the Artisan command:

   ```bash
   ./vendor/bin/sail artisan make:user
   ```

   This will prompt you for the necessary user details.


8. **Genrate user token via Artisan Command**

   To generate a token for the user, use the following command:

   ```bash
   ./vendor/bin/sail artisan make:user-token {user_id}
   ```

    Replace `{user_id}` with the actual ID of the user you want to generate a token for.

9. **Fill database with sample data (optional)**

   If you want to fill the database with sample data, you can run:

   ```bash
   ./vendor/bin/sail artisan seed:tasks
   ```
    It will prompt you for the number of tasks to generate. Enter a number to create that many tasks.

