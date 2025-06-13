# Profit Dashboard

Profit Dashboard is a Symfony 6 application that connects a Shopify store with Facebook to provide a single dashboard for tracking sales and advertising performance. It authenticates with Shopify, retrieves orders and products, reads Facebook ad spending and computes profitability metrics.

## Prerequisites

- **PHP 8.1+** with Composer
- **Node.js** and **npm**
- **Docker** and **Docker Compose** (see `docker-compose.yaml`)

## Setup

1. Install PHP dependencies:
   ```bash
   composer install
   ```
2. Install JavaScript dependencies:
   ```bash
   npm install
   ```
3. Copy and configure environment variables:
   ```bash
   cp .env.local.dist .env.local
   # edit .env.local with your Shopify, Facebook and database credentials
   ```
4. Start the containers and the database:
   ```bash
   docker-compose up -d
   ```
5. Run the database migrations:
   ```bash
   bin/console doctrine:migrations:migrate
   ```

## Building Assets

- Development build with auto–refresh:
  ```bash
  npm run dev
  ```
- Production build:
  ```bash
  npm run build
  ```

## Running the Application

You can run Symfony locally using the Symfony CLI:
```bash
symfony serve
```

Alternatively, the Docker container runs Apache via the entrypoint script `docker/app/entrypoint.sh` when using `docker-compose up`.

The application will be available at <http://localhost:8000>.
