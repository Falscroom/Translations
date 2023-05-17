#!/bin/bash
docker run -it --rm --init -v "${PWD}":/opt/workdir php:8.2-alpine "$@"