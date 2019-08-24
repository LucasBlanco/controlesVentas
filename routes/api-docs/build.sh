#!/bin/bash
if [ `npm list -g | grep -c speccy` -eq 0 ]; then
    sudo npm install speccy -g
fi

if [ -d "./dist" ]
then
  rm -r ./dist
fi

mkdir ./dist

for file in *.yaml
do
    speccy resolve ${file} -o "dist/${file}"
done