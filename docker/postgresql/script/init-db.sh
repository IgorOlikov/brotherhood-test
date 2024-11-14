#!/bin/bash

until pg_isready -U "${POSTGRES_USER}" -h "${POSTGRES_HOST}" -p 5432; do
  echo "Waiting for PostgreSQL to be ready..."
  sleep 2
done

psql -U app -tc "SELECT 1 FROM pg_database WHERE datname = 'app_test'" | grep -q 1 || psql -U app -c "CREATE DATABASE app_test"

tail -f /dev/null
