#!/bin/bash
/usr/bin/php ./symfony propel:build-model
/usr/bin/php ./symfony propel:build-forms
/usr/bin/php ./symfony propel:build-filters
php ./symfony cc