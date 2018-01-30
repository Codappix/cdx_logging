<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'TYPO3 logging',
    'description' => 'Provides features for TYPO3 logging',
    'category' => 'misc',
    'version' => '0.0.1',
    'state' => 'alpha',
    'author' => 'Codappix',
    'author_email' => 'info@codappix.net',
    'author_company' => 'Codappix',
    'constraints' => [
        'depends' => [
            'typo3' => '8.7.0-8.7.999',
            'php' => '7.0.0-7.1.999',
        ],
    ],
];
