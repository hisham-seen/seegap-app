# Superset Customization Guide

## Theme Customization

1. Modify the theme colors in `src/theme/light.ts`:
   ```typescript
   // Example: Change primary color
   export const lightTheme = {
     colors: {
       primary: {
         base: '#YOUR_PRIMARY_COLOR',
         // other color variants...
       },
       // other color definitions...
     },
     // other theme properties...
   };
   ```

2. Adjust global styles in `src/GlobalStyles.tsx` if needed.

## Branding Customization

1. Replace logo files in `src/assets/branding/` with your own:
   - Replace `superset-logo-horiz.svg` and `.png` with your horizontal logo
   - Replace `superset-logo-stacked.svg` and `.png` with your stacked logo

2. Update the favicon in `src/assets/images/favicon.png`

## CSS Customization

1. Modify `src/assets/stylesheets/superset.less` for global styles
2. Add custom styles to specific component files as needed

## Building Your Changes

After making your customizations:

1. Install dependencies:
   ```bash
   npm install
   ```

2. Build the frontend assets:
   ```bash
   npm run build
   ```

## Creating a Custom Docker Image

Create a Dockerfile that builds from your customized source:

```dockerfile
FROM node:16 AS frontend-builder

WORKDIR /app/superset-frontend
COPY superset-frontend .
RUN npm install && npm run build

FROM apache/superset:latest

# Copy built frontend assets
COPY --from=frontend-builder /app/superset-frontend/dist /app/superset/superset/static/assets

# Copy any additional configuration files
COPY ./config/superset_config.py /app/pythonpath/
```

Build and tag your custom image:
```bash
docker build -t your-org/custom-superset:latest .
```
