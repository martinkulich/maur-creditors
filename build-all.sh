#!/bin/bash
php ./symfony propel:build-model
php ./symfony propel:build-forms
php ./symfony propel:build-filters
php ./symfony cc