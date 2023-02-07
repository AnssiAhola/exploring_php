#!/bin/bash

mkdir $1 -p
cd ./$1
composer init --name=anssiahola/$1 --no-interaction
composer dump-autoload
touch index.php
echo -e "<?php\nrequire_once('./vendor/autoload.php');\n" > index.php