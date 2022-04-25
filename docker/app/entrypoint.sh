#!/bin/bash

echo "Starting apache2 ..."
service apache2 start

echo "Starting local tunnel ..."
lt -s profitdashboard -p 8000
