# Main Application

This repository contains the main application.

## Directory Structure

- `app/`: Contains the application code
  - `controllers/`: Application controllers
  - `models/`: Application models
  - `views/`: Application views
  - `helpers/`: Helper functions
  - `includes/`: Include files
  - `languages/`: Language files
  - `traits/`: PHP traits
- `docker/`: Docker configuration files
- `themes/`: Application themes
- `plugins/`: Application plugins
- `uploads/`: File uploads directory
- `config.php`: Main configuration file
- `index.php`: Application entry point

## Setup and Run

1. Make sure Docker and Docker Compose are installed on your system.

2. Copy the environment file and configure your settings:
   ```bash
   cp .env.example .env
   ```

3. Start the application:
   ```bash
   docker-compose up -d
   ```

4. Access the application at http://localhost

## Configuration

The main configuration is located in `config.php`. Environment-specific settings can be configured in the `.env` file.

## Development

The application uses a Docker-based development environment with:
- Nginx web server
- PHP-FPM
- MySQL database

To make changes to the application, edit the files in the `app/` directory. The Docker setup includes volume mounts for live reloading during development.
