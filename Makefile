ARGS := $(wordlist 2,$(words $(MAKECMDGOALS)),$(MAKECMDGOALS))

.PHONY: help test snapshots supported-parameters stan cs cs-fix

help:
	@echo "  make test                 - Run PHPUnit tests"
	@echo "  make snapshots            - Rewrite the three snapshot fixtures, then review their diff"
	@echo "  make supported-parameters - Regenerate SupportedParameters.php from data/sdk-supported-parameters.json"
	@echo "  make stan                 - Run PHPStan"
	@echo "  make cs                   - Check code style"
	@echo "  make cs-fix               - Fix code style"

test:
	@vendor/bin/phpunit $(ARGS)

# Rewrites the fixtures of the three snapshot tests instead of comparing against them:
#   tests/Fixtures/signature-snapshot.txt  the signatures of the public classes (SignatureSnapshotTest)
#   tests/Fixtures/wire-snapshot.txt       the requests the SDK sends (WireSnapshotTest)
#   tests/Fixtures/getter-snapshot.txt     the value of every getter for the synthetic answers (GetterSnapshotTest)
# Run it only after a change that is meant to change one of them. Then review the git diff of the
# fixtures: it must show exactly the intended change. The three tests report themselves as skipped
# while they write, so run make test afterwards to check the written fixtures.
snapshots:
	@SIGNATURE_SNAPSHOT_UPDATE=1 WIRE_SNAPSHOT_UPDATE=1 GETTER_SNAPSHOT_UPDATE=1 vendor/bin/phpunit --filter SnapshotTest $(ARGS)

# Regenerates src/Justimmo/Model/Mapper/V1/SupportedParameters.php from
# data/sdk-supported-parameters.json, which is generated from the API specification and copied
# here unchanged. Run it after copying a new json. Then review the git diff of
# SupportedParameters.php: it must show exactly the change of the json. A json which differs only
# in generated_at changes nothing, so it is not committed. Run make test afterwards:
# SupportedParametersTest fails while the php does not match the json.
supported-parameters:
	@php bin/generate-supported-parameters.php

stan:
	@vendor/bin/phpstan $(ARGS)

cs:
	@vendor/bin/php-cs-fixer fix --dry-run --diff $(ARGS)

cs-fix:
	@vendor/bin/php-cs-fixer fix $(ARGS)

%:
	@:
