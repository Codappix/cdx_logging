mkfile_path := $(abspath $(lastword $(MAKEFILE_LIST)))
current_dir := $(dir $(mkfile_path))

TYPO3_WEB_DIR := $(current_dir).Build/web
TYPO3_PATH_ROOT := $(current_dir).Build/web
# Allow different versions on travis
TYPO3_VERSION ?= ~6.2

.PHONY: install
install: clean
	COMPOSER_PROCESS_TIMEOUT=1000 composer require -vv --dev --prefer-dist --ignore-platform-reqs typo3/cms="$(TYPO3_VERSION)"
	git checkout composer.json

unitTests:
	TYPO3_PATH_WEB=$(TYPO3_WEB_DIR) \
	vendor/bin/phpunit --colors --debug -v

clean:
	rm -rf .Build composer.lock vendor
