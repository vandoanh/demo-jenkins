#!/usr/bin/env bash
php artisan queue:work --queue=default,crawler,image,log --sleep=3 --tries=3
