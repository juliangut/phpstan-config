default: lint


# Linting
# phplint 9.8 ignores cache-dir and writes its cache under the system temp dir: TMPDIR moves it to tools/.
.PHONY: lint-php
lint-php:
	@$(eval lint_format ?= console)
	@mkdir -p tools/.phplint.cache
	@TMPDIR=$(CURDIR)/tools/.phplint.cache PLINT_DIAGNOSTIC=never vendor/bin/phplint lint --configuration=./tools/phplint.yml --no-progress --output-format=$(lint_format) --ansi

.PHONY: lint-ecs
lint-ecs:
	@$(eval ecs_format ?= console)
	@vendor/bin/ecs check --config=./tools/ecs.php --output-format=$(ecs_format) --ansi

.PHONY: lint
lint: lint-php lint-ecs


# Fix
.PHONY: fix-ecs
fix-ecs:
	@$(eval ecs_format ?= console)
	@vendor/bin/ecs check --config=./tools/ecs.php --fix --output-format=$(ecs_format) --ansi

.PHONY: fix
fix: fix-ecs


# Analysis
# Both caches return stale results after an edit: pdepend's is emptied on every run, phpmd's is off.
.PHONY: analyse-phpmd
analyse-phpmd:
	@$(eval phpmd_format ?= ansi)
	@rm -rf tools/.pdepend.cache && mkdir -p tools/.pdepend.cache
	@XDG_CACHE_HOME=$(CURDIR)/tools/.pdepend.cache vendor/bin/phpmd analyze tools/ecs.php src --format $(phpmd_format) --ruleset tools/phpmd.xml.dist --no-progress

.PHONY: analyse-phpmnd
analyse-phpmnd:
	@vendor/bin/phpmnd --ansi tools/ecs.php src

.PHONY: analyse-compatibility
analyse-compatibility:
	@$(eval compatibility_format ?= full)
	@vendor/bin/phpcs --standard=./tools/phpcompatibility.xml.dist --report=$(compatibility_format) tools/ecs.php src --cache=./tools/.phpcompatibility.cache

.PHONY: analyse-phpstan
analyse-phpstan:
	@$(eval phpstan_format ?= ticketswap)
	@vendor/bin/phpstan analyse --configuration=./tools/phpstan.neon.dist --memory-limit=2G --error-format=$(phpstan_format) --no-progress --ansi -v

.PHONY: analyse
analyse: analyse-phpmd analyse-phpmnd analyse-compatibility analyse-phpstan


# Testing
.PHONY: test-phpunit
test-phpunit:
	@$(eval phpunit_format ?= testdox)
	@vendor/bin/phpunit --configuration=./tools/phpunit.xml.dist --$(phpunit_format)

.PHONY: test-infection
test-infection:
	@vendor/bin/infection --configuration=./tools/infection.json.dist

.PHONY: test
test: test-phpunit test-infection


# Coverage
.PHONY: report-phpunit-coverage
report-phpunit-coverage:
	@vendor/bin/phpunit --configuration=./tools/phpunit.xml.dist --coverage-html build/coverage

.PHONY: report-phpunit-clover
report-phpunit-clover:
	@vendor/bin/phpunit --configuration=./tools/phpunit.xml.dist --coverage-clover build/logs/clover.xml

.PHONY: report
report: report-phpunit-coverage report-phpunit-clover
