FROM php:7.4

ARG SSH_PRV_KEY
ARG USERNAME
ARG EMAIL

RUN apt-get update -y --fix-missing \
	&& apt-get install -y \
        git \
        libzip-dev \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libmcrypt-dev \
        libpng-dev
RUN docker-php-ext-install zip

# add e.coding.net to known hosts
RUN mkdir -p /root/.ssh && ssh-keyscan e.coding.net > /root/.ssh/known_hosts

# Add the keys and set permissions
RUN echo "$SSH_PRV_KEY" > /root/.ssh/id_rsa && chmod 600 /root/.ssh/id_rsa
# check if ssh agent is running or not,if not,run
RUN eval `ssh-agent -s` && ssh-add /root/.ssh/id_rsa

# config git
RUN git config --global core.fileMode false
RUN git config --global user.name "$USERNAME"
RUN git config --global user.email "$EMAIL"

RUN git clone "https://github.com/luo3555/STCodeStandard.git" /var/CodeStandard/STCodeStandard
RUN curl "https://mirrors.aliyun.com/composer/composer.phar" --output /bin/composer && chmod +x /bin/composer
RUN composer config --global --auth github-oauth.github.com b442a874523b6f97045e4f6f22d5e13f3bbb6ccf && \
    composer global require "squizlabs/php_codesniffer=*" && \
    composer global require "magento/magento-coding-standard"
RUN ln -s /root/.composer/vendor/squizlabs/php_codesniffer/bin/phpcs /bin/phpcs && \
    ln -s /root/.composer/vendor/squizlabs/php_codesniffer/bin/phpcbf /bin/phpcbf && \
    phpcs --config-set installed_paths /root/.composer/vendor/magento/magento-coding-standard,/var/CodeStandard/STCodeStandard/STCodeStandard && \
    phpcs --config-set default_standard Magento2,STCodeStandard && \
    phpcs --config-set tab_width 4 && \
    phpcs --config-set show_warnings 0 && \
    phpcs --config-set colors 1 
# clean cache
COPY vcs.sh /bin/vcs
COPY fix.sh /bin/fix
RUN chmod +x /bin/vcs
RUN chmod +x /bin/fix
VOLUME ["/usr/share/nginx/html", "/var/CodeStandard"]

WORKDIR /usr/share/nginx/html
ENTRYPOINT [ "/bin/bash" ]

# how to use
# 1. build image
# docker build --build-arg SSH_PRV_KEY="$(cat /home/YOUR_ACCOUNT/.ssh/YOUR_PRIVATE_KEY_FILE_NAME)" --build-arg USERNAME="UserName" --build-arg EMAIL="user@example.com" -t code_standard .
# 2. entry container
# docker run -it -v /var/www/code:/usr/share/nginx/html --rm --name code_standard code_standard
