#!/bin/sh

mkdir dist
git archive HEAD --prefix=n1ed/ --format=zip -o dist/n1ed-wordpress.zip