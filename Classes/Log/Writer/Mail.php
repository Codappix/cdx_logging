<?php
namespace Codappix\CdxLogging\Log\Writer;

/*
 * Copyright (C) 2018  Daniel Siepmann <coding@daniel-siepmann.de>
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

use TYPO3\CMS\Core\Log\LogLevel;
use TYPO3\CMS\Core\Log\LogRecord;
use TYPO3\CMS\Core\Log\Writer\AbstractWriter;
use TYPO3\CMS\Core\Mail\MailMessage;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\MailUtility;

/**
 * Send log entries as mail.
 */
class Mail extends AbstractWriter
{
    protected $from = [];
    protected $to = [];

    public function __construct(array $options = [])
    {
        $this->from = MailUtility::getSystemFrom();

        parent::__construct($options);
    }

    public function setFrom(array $from)
    {
        $this->from = $from;
    }

    public function setTo(array $to)
    {
        $this->to = $to;
    }

    public function writeLog(LogRecord $record)
    {
        GeneralUtility::makeInstance(MailMessage::class)
            ->setSubject($this->getSubject($record))
            ->setFrom($this->from)
            ->setTo($this->to)
            ->setBody($this->getBody($record))
            ->send()
            ;
    }

    protected function getSubject(LogRecord $record)
    {
        return LogLevel::getName($record->getLevel()) . ' - ' . $record->getMessage();
    }

    protected function getBody(LogRecord $record)
    {
        return implode("\r\n", [
            $this->getSubject($record),
            'Created: ' . $record->getCreated(),
            'RequestId: ' . $record->getRequestId(),
            'Component: ' . $record->getComponent(),
            'Data: ' . var_export($record->getData(), true),
        ]);
    }
}
