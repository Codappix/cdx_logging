.. _highlight: php

CDX Logging
===========

We always use the TYPO3 Logging API. This way we are able to configure different logging for local,
staging and production. Very helpful for cronjobs, e.g. scheduler tasks.

For local dev we can use one of the console writer, see :ref:`logging_logWriter`.

.. _logging_logWriter:

LogWriter
---------

The extension provides two further log writer for TYPO3:

Console
    Will output all log entries without buffering to `stdout`.
    Output will not be styled in any way. Messages are displayed plain, prefixed with log level as
    string representation.

AnsiConsole
    Will output all log entries without buffering to `stdout`.
    Output will not be styled. Messages are displayed colored depending on their log level.

Mail
    Will send a single mail for each log record.
    Mails are send as plain text and multiple sender can be configured.
    Possible options are `from` and `to` accordingly to
    https://docs.typo3.org/typo3cms/CoreApiReference/ApiOverview/Mail/Index.html#how-to-create-and-send-mails

Example configuration::

    'LOG' => [
        'Codappix' => [
            'CdxSite' => [
                'Command' => [
                    'writerConfiguration' => [
                        [
                            \Codappix\CdxLogging\Log\Writer\AnsiConsole::class => [
                                'stream' => 'php://stderr',
                                'dataOutput' => true,
                            ],
                            \Codappix\CdxLogging\Log\Writer\Mail::class => [
                                'to' => [
                                    'address@example.com' => '1st Example Name',
                                    'address2@example.com' => '2nd Example Name',
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

The above example will use the ``AnsiConsole`` for all ``CommandController`` inside the TYPO3
Extension ``cdx_site`` for all log levels.

Also it configured the console to write all entries to ``stderr``, default is ``stdout``. Currently
there is no option to define a certain severity to be displayed to ``stderr`` while others are
displayed to ``stdout``.

Also it configured the add the provided data, if any. Default is to not add data.
Data is added in json format.

As a stream is expected, this is everything that can be handled as a stream, also files, etc.
