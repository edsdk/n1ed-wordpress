#!/bin/sh

git archive HEAD --prefix=n1ed/ --worktree-attributes --format=zip -o dist/n1ed-wordpress.zip
