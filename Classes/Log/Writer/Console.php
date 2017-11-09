<?php
namespace Codappix\CdxLogging\Log\Writer;

/*
 * Copyright (C) 2017  Daniel Siepmann <coding@daniel-siepmann.de>
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
 * 02110-1301, USA.
 */

use TYPO3\CMS\Core\Log\Writer\AbstractWriter;
use TYPO3\CMS\Core\Log\LogLevel;

/**
 * Adapted from https://github.com/neos/flow/
 */
class Console extends AbstractWriter
{
    /**
     * An array of severity labels, indexed by their integer constant
     * @var array
     */
    protected $severityLabels;

    /**
     * @var resource
     */
    protected $streamHandle;

    /**
     * @var bool
     */
    protected $dataOutput = false;

    /**
     * @throws CouldNotOpenResourceException If stream could not be opened.
     */
    public function __construct(array $options = ['stream' => 'php://stdout', 'dataOutput' => false])
    {
        $this->severityLabels = [
            LOG_EMERG   => 'EMERGENCY',
            LOG_ALERT   => 'ALERT    ',
            LOG_CRIT    => 'CRITICAL ',
            LOG_ERR     => 'ERROR    ',
            LOG_WARNING => 'WARNING  ',
            LOG_NOTICE  => 'NOTICE   ',
            LOG_INFO    => 'INFO     ',
            LOG_DEBUG   => 'DEBUG    ',
        ];

        if (isset($options['dataOutput'])) {
            $this->dataOutput = (bool) $options['dataOutput'];
        }

        if (!isset($options['stream'])) {
            $options['stream'] = 'php://stdout';
        }
        $this->streamHandle = @fopen($options['stream'], 'w');

        if (!is_resource($this->streamHandle)) {
            throw new CouldNotOpenResourceException(
                'Could not open stream "' . $options['stream'] . '" for write access.',
                1310986609
            );
        }
    }

    public function writeLog(\TYPO3\CMS\Core\Log\LogRecord $record)
    {
        $output = sprintf(
            '%s: %s %s',
            $record->getComponent(),
            $record->getMessage(),
            $this->getAdditionalDataOutput($record->getData())
        );
        if (is_resource($this->streamHandle)) {
            fputs($this->streamHandle, trim($output) . PHP_EOL);
        }

        return $this;
    }

    protected function getAdditionalDataOutput($additionalData = null)
    {
        if ($this->dataOutput && $additionalData) {
            return ' ' . json_encode($additionalData);
        }

        return '';
    }
}
