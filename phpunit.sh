#!/bin/bash

( sleep 1; touch src/Illuminate/Support/Traits/NullableFieldsTrait.php
) &

while true; do
	(find . -name \*php; echo composer.json) |
		inotifywait -e  modify -e move -e create -e attrib -e delete \
		   --fromfile - 2>/dev/null
	echo;
	echo;
	echo;


	vendor/bin/phpunit --stop-on-error --stop-on-failure

	sleep 0.1
done 

