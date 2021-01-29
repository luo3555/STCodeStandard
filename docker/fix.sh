#!/bin/sh
phpcs -n $1
phpcbf $1
phpcs -n $1