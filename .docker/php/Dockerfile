FROM php:8.1-fpm

# This Dockerfile adds a non-root user with sudo access. Use the "remoteUser"
# property in devcontainer.json to use it. On Linux, the container user's GID/UIDs
# will be updated to match your local UID/GID (when using the dockerFile property).
# See https://aka.ms/vscode-remote/containers/non-root-user for details.
ARG USERNAME=vscode
ARG USER_UID=1000
ARG USER_GID=$USER_UID

RUN apt-get update \
    && apt-get install -y git \
    && apt-get install -y unzip \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN echo 'xdebug.mode=debug' >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo 'xdebug.client_port=9003' >>  /usr/local/etc/php/conf.d/xdebug.ini \
    && echo 'xdebug.start_with_request = yes' >>  /usr/local/etc/php/conf.d/xdebug.ini \
    && echo 'xdebug.client_host = "127.0.0.1"' >>  /usr/local/etc/php/conf.d/xdebug.ini \
    && echo 'xdebug.idekey = VSCODE' >>  /usr/local/etc/php/conf.d/xdebug.ini \
    && echo 'xdebug.log_level = 0' >>  /usr/local/etc/php/conf.d/xdebug.ini


# Create a non-root user to use if preferred - see https://aka.ms/vscode-remote/containers/non-root-user.
RUN groupadd --gid $USER_GID $USERNAME \
&& useradd -s /bin/bash --uid $USER_UID --gid $USER_GID -m $USERNAME \
# [Optional] Add sudo support for the non-root user
&& apt-get install -y sudo \
&& echo $USERNAME ALL=\(root\) NOPASSWD:ALL > /etc/sudoers.d/$USERNAME\
&& chmod 0440 /etc/sudoers.d/$USERNAME

USER $USERNAME

WORKDIR /var/www/html