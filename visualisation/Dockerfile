# Custom Superset Dockerfile

# Build stage for the frontend assets
FROM node:16 AS frontend-builder

# Copy the frontend code
WORKDIR /app
COPY ./superset/superset-frontend/ ./superset-frontend/

# Install dependencies and build
WORKDIR /app/superset-frontend
RUN npm install && npm run build

# Final stage
FROM apache/superset:latest

# Copy built frontend assets
COPY --from=frontend-builder /app/superset-frontend/dist /app/superset/superset/static/assets

# Copy custom configuration
WORKDIR /app
RUN mkdir -p /app/pythonpath
COPY config/superset_config.py /app/pythonpath/

# Set the PYTHONPATH to include custom configurations
ENV PYTHONPATH=/app/pythonpath:$PYTHONPATH
