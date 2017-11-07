<?php
namespace Codappix\CdxLogging\Tests\Log\Writer;

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

use Codappix\CdxLogging\Log\Writer\Console;
use Codappix\CdxLogging\Log\Writer\CouldNotOpenResourceException;
use PHPUnit\Framework\TestCase;
use TYPO3\CMS\Core\Log\LogLevel;
use TYPO3\CMS\Core\Log\LogRecord;
use org\bovigo\vfs\vfsStream;

class ConsoleTest extends TestCase
{
    /**
     * @var Console
     */
    protected $subject;

    public function setUp()
    {
        vfsStream::setup('root', null, []);
        $this->subject = new Console(['stream' => vfsStream::url('root/output')]);
    }

    /**
     * @test
     * @dataProvider possibleLogRecords
     */
    public function writeLogOutputsLogRecordsAsExpected(int $logLevel, string $logMessage, string $expectedOutput)
    {
        $logRecord = new LogRecord(
            'Some Component, which is ignored by this logger.',
            $logLevel,
            $logMessage,
            [
                'Some data' => 'which is also ignored by this logger',
            ]
        );
        $this->subject->writeLog($logRecord);

        $this->assertSame(
            $expectedOutput . PHP_EOL,
            file_get_contents(vfsStream::url('root/output')),
            'Console Logger did not output the expected log.'
        );
    }

    public function possibleLogRecords() : array
    {
        return [
            'Debug log' => [
                LogLevel::DEBUG,
                'The message to log',
                'DEBUG     The message to log',
            ],
            'Info log' => [
                LogLevel::DEBUG,
                'The message to log',
                'DEBUG     The message to log',
            ],
            'Notice log' => [
                LogLevel::NOTICE,
                'The message to log',
                'NOTICE    The message to log',
            ],
            'Warning log' => [
                LogLevel::WARNING,
                'The message to log',
                'WARNING   The message to log',
            ],
            'Error log' => [
                LogLevel::ERROR,
                'The message to log',
                'ERROR     The message to log',
            ],
            'Critical log' => [
                LogLevel::CRITICAL,
                'The message to log',
                'CRITICAL  The message to log',
            ],
            'Alert log' => [
                LogLevel::ALERT,
                'The message to log',
                'ALERT     The message to log',
            ],
            'Emergency log' => [
                LogLevel::EMERGENCY,
                'The message to log',
                'EMERGENCY The message to log',
            ],
        ];
    }

    /**
     * @test
     */
    public function exceptionIsThrownOnInvalidStream()
    {
        $this->expectException(CouldNotOpenResourceException::class);
        new Console(['stream' => '']);
    }
}
