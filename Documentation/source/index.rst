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

Example configuration::

    'LOG' => [
        'Codappix' => [
            'CdxSite' => [
                'Command' => [
                    'writerConfiguration' => [
                        [
                            'Codappix\CdxLogging\Log\Writer\AnsiConsole' => [
                                'stream' => 'php://stderr',
                                'dataOutput' => true,
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
