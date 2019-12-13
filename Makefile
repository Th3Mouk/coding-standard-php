ci:
	vendor/bin/phpstan analyse && \
	vendor/bin/phpcs && \
	vendor/bin/phpunit
