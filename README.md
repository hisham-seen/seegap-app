# Superset Integration with Main Application

This repository contains a customized version of Apache Superset integrated with the main application.

## Directory Structure

- `visualisation/`: Contains the customized Superset setup
  - `superset/`: The official Apache Superset code
  - `config/`: Custom configuration files
  - `assets/`: Custom assets (logos, etc.)
  - `Dockerfile`: Builds a custom Superset image with our modifications
  - `docker-compose.yml`: Defines the services needed to run the custom Superset
  - `init.sh`: Script to initialize the custom Superset setup

## Setup and Run

1. Make sure Docker and Docker Compose are installed on your system.

2. Start the main application with the integrated Superset:
   ```bash
   docker-compose up -d
   ```

3. Initialize Superset (first time only):
   ```bash
   cd visualisation
   ./init.sh
   ```

4. Access Superset at http://localhost:8088
   - Default username: admin
   - Default password: admin

## Customization

The Superset customization is located in the `visualisation` directory. The main customizations include:

- Custom branding (logos, colors, etc.)
- Custom theme settings
- Custom CSS styles
- Configuration overrides

To make further customizations:

1. Modify the theme colors in `visualisation/superset/superset-frontend/src/theme/light.ts`
2. Update global styles in `visualisation/superset/superset-frontend/src/GlobalStyles.tsx`
3. Replace logo files in `visualisation/superset/superset-frontend/src/assets/branding/`
4. Update the favicon in `visualisation/superset/superset-frontend/src/assets/images/favicon.png`
5. Modify configuration settings in `visualisation/config/superset_config.py`

After making changes, rebuild the Docker image:
```bash
docker-compose build superviz
docker-compose up -d superviz
```

## Updating from Upstream

To incorporate updates from the official Apache Superset repository:

1. Navigate to the superset directory:
   ```bash
   cd visualisation/superset
   ```

2. Add the upstream remote (if not already added):
   ```bash
   git remote add upstream https://github.com/apache/superset.git
   ```

3. Fetch and merge changes:
   ```bash
   git fetch upstream
   git merge upstream/master
   ```

4. Resolve any conflicts and rebuild:
   ```bash
   cd ../..
   docker-compose build superviz
   docker-compose up -d superviz
   ```
