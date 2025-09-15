#!/bin/bash

# Set the port from Railway environment variable
PORT=${PORT:-80}

# Update Apache configuration with the correct port
sed -i "s/\${PORT}/$PORT/g" /etc/apache2/sites-available/000-default.conf

# Start Apache
apache2-foreground
