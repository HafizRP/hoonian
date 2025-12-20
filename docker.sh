#!/bin/bash

# Hoonian Docker Helper Script
# Usage: ./docker.sh [command]

set -e

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Function to print colored output
print_success() {
    echo -e "${GREEN}✓ $1${NC}"
}

print_error() {
    echo -e "${RED}✗ $1${NC}"
}

print_info() {
    echo -e "${YELLOW}ℹ $1${NC}"
}

# Check if .env exists
check_env() {
    if [ ! -f .env ]; then
        print_error ".env file not found!"
        print_info "Creating .env from .env.example..."
        cp .env.example .env
        print_info "Please edit .env file with your configuration"
        exit 1
    fi
}

# Commands
case "$1" in
    setup)
        print_info "Setting up Hoonian application..."
        
        # Check .env
        check_env
        
        # Build images
        print_info "Building Docker images..."
        docker compose build
        
        # Start containers
        print_info "Starting containers..."
        docker compose up -d
        
        # Wait for containers to be ready
        sleep 5
        
        # Check status
        print_info "Container status:"
        docker compose ps
        
        print_success "Setup complete!"
        print_info "Access application at: http://localhost:8004"
        ;;
        
    start)
        print_info "Starting containers..."
        docker compose up -d
        print_success "Containers started!"
        docker compose ps
        ;;
        
    stop)
        print_info "Stopping containers..."
        docker compose down
        print_success "Containers stopped!"
        ;;
        
    restart)
        print_info "Restarting containers..."
        docker compose restart
        print_success "Containers restarted!"
        ;;
        
    rebuild)
        print_info "Rebuilding containers..."
        docker compose down
        docker compose build --no-cache
        docker compose up -d
        print_success "Containers rebuilt!"
        ;;
        
    logs)
        if [ -z "$2" ]; then
            docker compose logs -f
        else
            docker compose logs -f "$2"
        fi
        ;;
        
    ps|status)
        docker compose ps
        ;;
        
    shell)
        print_info "Accessing app container shell..."
        docker compose exec app bash
        ;;
        
    migrate)
        print_info "Running migrations..."
        docker compose exec app php artisan migrate --force
        print_success "Migrations complete!"
        ;;
        
    seed)
        print_info "Seeding database..."
        docker compose exec app php artisan db:seed --force
        print_success "Seeding complete!"
        ;;
        
    fresh)
        print_info "Fresh migration (WARNING: This will drop all tables!)..."
        read -p "Are you sure? (yes/no): " confirm
        if [ "$confirm" = "yes" ]; then
            docker compose exec app php artisan migrate:fresh --force
            print_success "Fresh migration complete!"
        else
            print_info "Cancelled."
        fi
        ;;
        
    optimize)
        print_info "Optimizing application..."
        docker compose exec app php artisan optimize
        print_success "Optimization complete!"
        ;;
        
    clear)
        print_info "Clearing all caches..."
        docker compose exec app php artisan optimize:clear
        print_success "Caches cleared!"
        ;;
        
    artisan)
        shift
        docker compose exec app php artisan "$@"
        ;;
        
    composer)
        shift
        docker compose exec app composer "$@"
        ;;
        
    db)
        print_info "Accessing database..."
        docker compose exec app php artisan db
        ;;
        
    backup)
        print_info "Creating database backup..."
        BACKUP_FILE="backup-$(date +%Y%m%d_%H%M%S).sql"
        docker compose exec app php artisan db:backup > "$BACKUP_FILE"
        print_success "Backup created: $BACKUP_FILE"
        ;;
        
    restore)
        if [ -z "$2" ]; then
            print_error "Usage: ./docker.sh restore <backup-file.sql>"
            exit 1
        fi
        print_info "Restoring database from $2..."
        docker compose exec -T app php artisan db:restore < "$2"
        print_success "Database restored!"
        ;;
        
    clean)
        print_info "Cleaning up Docker resources..."
        docker compose down -v
        docker system prune -f
        print_success "Cleanup complete!"
        ;;
        
    update)
        print_info "Updating application..."
        git pull origin main || print_info "Not a git repository, skipping pull"
        docker compose build
        docker compose up -d
        docker compose exec app php artisan migrate --force
        docker compose exec app php artisan optimize
        print_success "Update complete!"
        ;;
        
    stats)
        docker stats
        ;;
        
    help|*)
        echo "Hoonian Docker Helper Script"
        echo ""
        echo "Usage: ./docker.sh [command]"
        echo ""
        echo "Commands:"
        echo "  setup       - Initial setup (build, start, migrate)"
        echo "  start       - Start containers"
        echo "  stop        - Stop containers"
        echo "  restart     - Restart containers"
        echo "  rebuild     - Rebuild containers from scratch"
        echo "  logs [svc]  - View logs (optionally for specific service)"
        echo "  ps|status   - Show container status"
        echo "  shell       - Access app container shell"
        echo "  migrate     - Run database migrations"
        echo "  seed        - Seed database"
        echo "  fresh       - Fresh migration (drops all tables)"
        echo "  optimize    - Optimize application"
        echo "  clear       - Clear all caches"
        echo "  artisan ... - Run artisan command"
        echo "  composer .. - Run composer command"
        echo "  db          - Access database CLI"
        echo "  backup      - Backup database"
        echo "  restore <f> - Restore database from backup file"
        echo "  clean       - Clean up Docker resources"
        echo "  update      - Update application (git pull, rebuild, migrate)"
        echo "  stats       - Show resource usage"
        echo "  help        - Show this help"
        echo ""
        echo "Examples:"
        echo "  ./docker.sh setup"
        echo "  ./docker.sh logs app"
        echo "  ./docker.sh artisan route:list"
        echo "  ./docker.sh composer require package/name"
        ;;
esac
