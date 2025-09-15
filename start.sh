#!/bin/bash

# Set the port from Railway environment variable
PORT=${PORT:-80}

# Update Apache configuration with the correct port
sed -i "s/\${PORT}/$PORT/g" /etc/apache2/sites-available/000-default.conf

# Update Apache to listen on the correct port
echo "Listen $PORT" >> /etc/apache2/ports.conf

# Start Apache
apache2-foreground
