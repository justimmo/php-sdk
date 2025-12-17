ARGS := $(wordlist 2,$(words $(MAKECMDGOALS)),$(MAKECMDGOALS))

.PHONY: help test stan

help:
	@echo "  make test - Run PHPUnit tests"
	@echo "  make stan - Run PHPStan"

test:
	@vendor/bin/phpunit $(ARGS)

stan:
	@vendor/bin/phpstan $(ARGS)

%:
	@:
