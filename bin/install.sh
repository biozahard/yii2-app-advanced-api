#!/usr/bin/env bash


php yii migrate --interactive=0

php yii migrate --migrationPath=@yii/rbac/migrations --interactive=0

