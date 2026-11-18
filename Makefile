default: lint


.PHONY: lint-php
lint-php:
	vendor/bin/phplint lint --configuration=.phplint.yml --ansi

.PHONY: lint-ecs
lint-ecs:
	vendor/bin/ecs check --ansi

.PHONY: lint
lint:
	make --no-print-directory lint-php && \
	make --no-print-directory lint-ecs


.PHONY: fix-ecs
fix-ecs:
	vendor/bin/ecs check --fix --ansi

.PHONY: fix
fix:
	make --no-print-directory fix-ecs


.PHONY: analyse-phpmd
analyse-phpmd:
	vendor/bin/phpmd analyze --format=ansi --ruleset=unusedcode --ruleset=naming --ruleset=design --ruleset=controversial --ruleset=codesize src

.PHONY: analyse-phpmnd
analyse-phpmnd:
	vendor/bin/phpmnd --ansi src

.PHONY: analyse-compatibility
analyse-compatibility:
	vendor/bin/phpcs --standard=PHPCompatibility --runtime-set testVersion 8.2- src

.PHONY: analyse-phpstan
analyse-phpstan:
	vendor/bin/phpstan analyse --configuration=phpstan.neon.dist --memory-limit=2G --no-progress

.PHONY: analyse
analyse:
	make --no-print-directory analyse-phpmd && \
	make --no-print-directory analyse-phpmnd && \
	make --no-print-directory analyse-compatibility && \
	make --no-print-directory analyse-phpstan


.PHONY: test-phpunit
test-phpunit:
	vendor/bin/phpunit

.PHONY: test-infection
test-infection:
	vendor/bin/infection

.PHONY: test
test:
	make --no-print-directory test-phpunit && \
	make --no-print-directory test-infection


.PHONY: report-phpunit-coverage
report-phpunit-coverage:
	vendor/bin/phpunit --coverage-html build/coverage

.PHONY: report-phpunit-clover
report-phpunit-clover:
	vendor/bin/phpunit --coverage-clover build/logs/clover.xml

.PHONY: report
report:
	make --no-print-directory report-phpunit-coverage && \
	make --no-print-directory report-phpunit-clover
