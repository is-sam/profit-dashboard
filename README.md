# Profit Dashboard

Profit Dashboard is a Shopify App built fully with Symfony 6. It's an app that connects a Shopify store and different marketing plateforms like Facebook Ads to provide a single dashboard for tracking sales, expenses and most importantly: PROFIT! It authenticates with Shopify, retrieves orders and products, reads Facebook ad spending and computes profitability metrics bases on cost of goods, shipping and other expenses.

## Prerequisites
- **Docker** and **Docker Compose** (see `docker-compose.yaml`)

## Setup

1. Start the containers:
   ```bash
   docker-compose up -d
   ```
2. Install PHP dependencies:
   ```bash
   composer install
   ```
3. Install JavaScript dependencies:
   ```bash
   npm install
   ```
4. Configure environment variables:
   ```bash
   cp .env.local.dist .env.local
   # edit .env.local with your Shopify, Facebook and database credentials
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
