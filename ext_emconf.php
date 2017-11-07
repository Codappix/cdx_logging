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
            'typo3' => '6.2.0-6.2.999',
            'php' => '5.6.0-5.6.999',
        ],
    ],
];
