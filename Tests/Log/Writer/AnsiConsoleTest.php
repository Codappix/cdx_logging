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

use Codappix\CdxLogging\Log\Writer\AnsiConsole;
use PHPUnit\Framework\TestCase;
use org\bovigo\vfs\vfsStream;
use TYPO3\CMS\Core\Log\LogRecord;
use TYPO3\CMS\Core\Log\LogLevel;

class AnsiConsoleTest extends TestCase
{
    /**
     * @var AnsiConsole
     */
    protected $subject;

    public function setUp()
    {
        vfsStream::setup('root', null, []);
        $this->subject = new AnsiConsole(['stream' => vfsStream::url('root/output')]);
    }

    /**
     * @test
     * @dataProvider possibleLogRecords
     */
    public function writeLogOutputsLogRecordsAsExpected($logLevel, $logMessage, $expectedOutput)
    {
        $logRecord = new LogRecord(
            'Some Component',
            $logLevel,
            $logMessage,
            [
                'Some data' => 'which is ignored by this logger',
            ]
        );
        $this->subject->writeLog($logRecord);

        $this->assertSame(
            $expectedOutput . PHP_EOL,
            file_get_contents(vfsStream::url('root/output')),
            'Console Logger did not output the expected log.'
        );
    }

    public function possibleLogRecords()
    {
        return [
            'Debug log' => [
                LogLevel::DEBUG,
                'The message to log',
                'Some Component: ' . AnsiConsole::FG_BLUE . 'The message to log' . AnsiConsole::END,
            ],
            'Info log' => [
                LogLevel::INFO,
                'The message to log',
                'Some Component: ' . AnsiConsole::FG_GREEN . 'The message to log' . AnsiConsole::END,
            ],
            'Notice log' => [
                LogLevel::NOTICE,
                'The message to log',
                'Some Component: ' . AnsiConsole::FG_WHITE . 'The message to log' . AnsiConsole::END,
            ],
            'Warning log' => [
                LogLevel::WARNING,
                'The message to log',
                'Some Component: ' . AnsiConsole::FG_YELLOW . 'The message to log' . AnsiConsole::END,
            ],
            'Error log' => [
                LogLevel::ERROR,
                'The message to log',
                'Some Component: ' . AnsiConsole::FG_RED . 'The message to log' . AnsiConsole::END,
            ],
            'Critical log' => [
                LogLevel::CRITICAL,
                'The message to log',
                'Some Component: ' . AnsiConsole::FG_BLACK . AnsiConsole::BG_CYAN . 'The message to log' . AnsiConsole::END,
            ],
            'Alert log' => [
                LogLevel::ALERT,
                'The message to log',
                'Some Component: ' . AnsiConsole::FG_BLACK . AnsiConsole::BG_YELLOW . 'The message to log' . AnsiConsole::END,
            ],
            'Emergency log' => [
                LogLevel::EMERGENCY,
                'The message to log',
                'Some Component: ' . AnsiConsole::FG_BLACK . AnsiConsole::BG_RED . 'The message to log' . AnsiConsole::END,
            ],
        ];
    }

    /**
     * @test
     */
    public function additionalDataIsAddedtoOutput()
    {
        $this->subject = new AnsiConsole(['stream' => vfsStream::url('root/output'), 'dataOutput' => true]);
        $logRecord = new LogRecord(
            'Some Component',
            LogLevel::DEBUG,
            'Message',
            [
                'Some data' => 'which is added by this logger',
            ]
        );
        $this->subject->writeLog($logRecord);

        $this->assertSame(
            'Some Component: ' . AnsiConsole::FG_BLUE . 'Message' . AnsiConsole::END .
                '  {"Some data":"which is added by this logger"}' . PHP_EOL,
            file_get_contents(vfsStream::url('root/output')),
            'Console Logger did not output the expected log.'
        );
    }
}
