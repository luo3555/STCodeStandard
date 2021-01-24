#!/bin/sh
BASEDIR=$(pwd)/
DIRS=''
FILES=''
FLAG='access'
GITLIST=$(git status -s)

validateCodeStandard()
{
phpcbf $1
if [ 0 != `phpcs -n $1 | grep 'ERROR' | wc -l` ]; then
    FLAG='error'
    phpcs -n $1
fi
}

for line in $GITLIST
do
    if [ -f "$BASEDIR$line" ]; then
        FILES=$FILES' '$BASEDIR$line
    fi
    if [ -d "$BASEDIR$line" ]; then
        validateCodeStandard $DIRS$BASEDIR$line
    fi
done

validateCodeStandard $FILES

if [ "$FLAG" = "access" ]; then
    git status
    echo '=================================================================================='
    echo "\033[31mShow different?\033[0m  \033[32;40m[y/n]\033[0m"
    read -p ':' input
    if [ $input = 'y' ]; then
        git diff
    fi
    echo "\033[31mAdd all files and dirs to GIT remotes? if YES will do [$(pwd)/git add . && git push]\033[0m  \033[32;40m[y/n]\033[0m"
    read -p ":" inputpush
    if [ $inputpush = 'y' ]; then
        # do git push
        git add .
        git push
    fi
else
    echo "\033[31mYou should fix all errors and try again. current nothing be pushed.\033[0m"
fi