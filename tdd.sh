#!/usr/bin/env bash

# https://lists.ubuntu.com/archives/ubuntu-users/2009-October/199571.html
while [ true ] ; do
    if [ $# -eq 0 ]
    then
        docker-compose exec app composer test
    else
        docker-compose exec app composer test $@
    fi
    read -p "Press any key to repeat or Ctrl-C to cancel..." -n1 -s
    echo $'\n'
done
