# Custom Superset configuration file

# Branding configuration
APP_NAME = "My Custom Superset"
APP_ICON = "/static/assets/images/custom_logo.png"
LOGO_TARGET_PATH = "https://mycompany.com"

# Theme configuration
THEME = {
    "brandColor": "#1A85C8",
    "secondaryColor": "#5AAF29",
    "accentColor": "#FF5A5F",
    "backgroundColor": "#FFFFFF",
    "textColor": "#323232",
    "linkColor": "#1A85C8",
}

# Custom CSS for the entire application
CUSTOM_CSS = """
.navbar {
    background-color: #1A85C8 !important;
}
.navbar-brand img {
    height: 40px;
}
"""

# Feature flags
FEATURE_FLAGS = {
    "DASHBOARD_NATIVE_FILTERS": True,
    "DASHBOARD_CROSS_FILTERS": True,
    "DASHBOARD_NATIVE_FILTERS_SET": True,
    "ENABLE_TEMPLATE_PROCESSING": True,
}
