#!/bin/bash

if ! command -v zip &> /dev/null
then
    echo "Zip could not be found"
    exit
fi
rm zip/n1ed-wordpress.zip
zip -r zip/n1ed-wordpress.zip . -x './zip.sh' './zip/*' './.git/*' './flmngr-cache/[!.]*'