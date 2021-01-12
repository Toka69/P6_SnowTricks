FROM php:8

COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/

#PHP extensions
RUN install-php-extensions mysqli pdo_mysql xdebug intl @composer

#Install GIT and ZIP
RUN apt-get update -y && \
    apt-get install git -y && \
    git clone https://github.com/xdebug/xdebug
RUN apt-get install zip -y && \
    apt-get install unzip -y

#Install Symfony CLI
RUN apt-get install wget -y && \
    wget https://get.symfony.com/cli/installer -O - | bash && \
    mv /root/.symfony/bin/symfony /usr/local/bin/symfony

WORKDIR workspaces/p6/