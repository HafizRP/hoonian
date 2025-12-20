#!/bin/bash
# Hoonian Docker Helper Script
set -e

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m'

print_success() { echo -e "${GREEN}✓ $1${NC}"; }
print_error() { echo -e "${RED}✗ $1${NC}"; }
print_info() { echo -e "${YELLOW}ℹ $1${NC}"; }

case "$1" in
    setup)
        print_info "Running full setup..."
        docker-compose build
        docker-compose up -d
        sleep 5
        docker-compose exec app php artisan migrate --force
        docker-compose exec app php artisan storage:link
        print_success "Setup completed!"
        ;;
    build) docker-compose build ;;
    start) docker-compose up -d ;;
    stop) docker-compose down ;;
    restart) docker-compose restart ;;
    logs) docker-compose logs -f app ;;
    migrate) docker-compose exec app php artisan migrate --force ;;
    shell) docker-compose exec app bash ;;
    *)
        echo "Usage: ./docker.sh {setup|build|start|stop|restart|logs|migrate|shell}"
        exit 1
        ;;
esac
