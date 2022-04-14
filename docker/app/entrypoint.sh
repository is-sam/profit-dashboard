#!/bin/bash

# composer install
# npm install
# npm run dev
# symfony console doctrine:migrations:migrate -n
# symfony server:stop
symfony serve -d
lt -s profitdashboard -p 8000
