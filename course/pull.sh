#!/bin/bash

git pull origin main

php artisan migrate

composer dump

exit
