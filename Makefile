ARGS := $(wordlist 2,$(words $(MAKECMDGOALS)),$(MAKECMDGOALS))

.PHONY: help test stan cs cs-fix

help:
	@echo "  make test   - Run PHPUnit tests"
	@echo "  make stan   - Run PHPStan"
	@echo "  make cs     - Check code style"
	@echo "  make cs-fix - Fix code style"

test:
	@vendor/bin/phpunit $(ARGS)

stan:
	@vendor/bin/phpstan $(ARGS)

cs:
	@vendor/bin/php-cs-fixer fix --dry-run --diff $(ARGS)

cs-fix:
	@vendor/bin/php-cs-fixer fix $(ARGS)

%:
	@:
