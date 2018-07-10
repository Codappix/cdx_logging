mkfile_path := $(abspath $(lastword $(MAKEFILE_LIST)))
current_dir := $(dir $(mkfile_path))

TYPO3_WEB_DIR := $(current_dir).Build/web
TYPO3_PATH_ROOT := $(current_dir).Build/web
# Allow different versions on travis
TYPO3_VERSION ?= ~8.7

sourceOrDist=--prefer-dist
ifeq ($(TYPO3_VERSION),~7.6)
	sourceOrDist=--prefer-source
endif

.PHONY: install
install: clean
	if [ $(TYPO3_VERSION) = ~7.6 ]; then \
		patch composer.json Tests/InstallPatches/composer.json.patch; \
	fi

	COMPOSER_PROCESS_TIMEOUT=1000 composer require -vv --dev $(sourceOrDist) typo3/cms="$(TYPO3_VERSION)"
	git checkout composer.json

lint:
	find . -name \*.php -not -path "./vendor/*" -not -path "./.Build/*" | parallel --gnu php -d display_errors=stderr -l {} > /dev/null \;
	.Build/bin/phpcs
	.Build/bin/phpstan analyze -c phpstan.dist.neon --level=max Classes/ Tests/ *.php

unitTests:
	TYPO3_PATH_WEB=$(TYPO3_WEB_DIR) \
		.Build/bin/phpunit --colors --debug -v

clean:
	rm -rf .Build composer.lock
