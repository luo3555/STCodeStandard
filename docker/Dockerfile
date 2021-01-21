FROM php:7.4
RUN apt-get update -y --fix-missing \
	&& apt-get install -y \
        git \
        libzip-dev \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libmcrypt-dev \
        libpng-dev
RUN docker-php-ext-install zip
RUN git clone "https://github.com/luo3555/STCodeStandard.git" /var/CodeStandard/STCodeStandard
RUN curl "https://mirrors.aliyun.com/composer/composer.phar" --output /bin/composer && chmod +x /bin/composer
RUN composer config --global --auth github-oauth.github.com b442a874523b6f97045e4f6f22d5e13f3bbb6ccf && \
    composer global require "squizlabs/php_codesniffer=*" && \
    composer global require "magento/magento-coding-standard"
RUN ln -s /root/.composer/vendor/squizlabs/php_codesniffer/bin/phpcs /bin/phpcs && \
    ln -s /root/.composer/vendor/squizlabs/php_codesniffer/bin/phpcbf /bin/phpcbf && \
    phpcs --config-set installed_paths /root/.composer/vendor/magento/magento-coding-standard,/var/CodeStandard/STCodeStandard/STCodeStandard
VOLUME ["/usr/share/nginx/html", "/var/CodeStandard", "/root/.ssh"]
WORKDIR /usr/share/nginx/html
ENTRYPOINT '/bin/bash'

# how to use
# 1. build image
# docker build -t code_standard .
# 2. entry container
# docker run -it -v /home/youaccount/.ssh:/root/.ssh -v /var/www/code:/usr/share/nginx/html --rm --name code_standard code_standard