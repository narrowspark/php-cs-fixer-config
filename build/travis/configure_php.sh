#!/usr/bin/env bash

if [[ "$REMOVE_XDEBUG" = true ]]; then
  phpenv config-rm xdebug.ini;
fi
