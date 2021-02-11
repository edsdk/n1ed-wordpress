#!/bin/bash

if ! command -v zip &> /dev/null
then
    echo "Zip could not be found"
    exit
fi
rm zip/n1ed-wordpress.zip
zip -r zip/n1ed-wordpress.zip . -i './flmngr-cache/.gitignore' '*.php' './js/*' './tmp/' './vendor/*'