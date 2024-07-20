FROM debian:bookworm

# Set environment variables
ENV USER=govind
ENV HOME=/var/www/html

# Create user and group
RUN groupadd -r $USER && useradd -r -m -g $USER $USER

# Install sudo
RUN apt-get update && apt-get install -y sudo

# Grant sudo privileges to the new user
RUN echo "$USER ALL=(ALL) NOPASSWD: ALL" > /etc/sudoers.d/$USER

# Switch to the new user
USER $USER
WORKDIR $HOME

# Install necessary packages
RUN sudo apt-get update && sudo apt-get install -y \
    apache2 \
    php \
    php-cli \
    libapache2-mod-php \
    php-mysql \
    php-redis \
    php-mongodb \
    curl \
    supervisor \
    git \
    unzip

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copy application files and adjust permissions
COPY --chown=$USER:$USER ../* $HOME/
RUN sudo chmod -R 775 $HOME

# Copy configuration files
COPY --chown=$USER:$USER docker/supervisord.conf /etc/supervisor/supervisord.conf
COPY --chown=$USER:$USER docker/php/apache2.conf /etc/apache2/sites-available/000-default.conf

# Run supervisord
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/supervisord.conf"]
