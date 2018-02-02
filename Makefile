mkfile_path := $(abspath $(lastword $(MAKEFILE_LIST)))
current_dir := $(dir $(mkfile_path))

TYPO3_WEB_DIR := $(current_dir).Build/web
TYPO3_PATH_ROOT := $(current_dir).Build/web
# Allow different versions on travis
TYPO3_VERSION ?= ~8.7

.PHONY: install
install: clean
	COMPOSER_PROCESS_TIMEOUT=1000 composer require -vv --dev --prefer-dist typo3/cms="$(TYPO3_VERSION)"
	git checkout composer.json
	mv .Build/vendor/typo3-ci/typo3cms .Build/vendor/squizlabs/php_codesniffer/CodeSniffer/Standards/TYPO3CMS
	mv .Build/vendor/typo3-ci/typo3sniffpool .Build/vendor/squizlabs/php_codesniffer/CodeSniffer/Standards/TYPO3SniffPool

lint:
	find . -name \*.php -not -path "./vendor/*" -not -path "./.Build/*" | parallel --gnu php -d display_errors=stderr -l {} > /dev/null \;
	.Build/bin/phpcs
	.Build/bin/phpstan analyze -c phpstan.dist.neon --level=max Classes/ Tests/ *.php

unitTests:
	TYPO3_PATH_WEB=$(TYPO3_WEB_DIR) \
		.Build/bin/phpunit --colors --debug -v

clean:
	rm -rf .Build composer.lock
