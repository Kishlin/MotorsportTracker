<?php

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__.'/apps/MotorsportTracker/Backend/tests')
    ->in(__DIR__.'/apps/MotorsportTracker/Backend/src')
    ->in(__DIR__.'/apps/Backoffice/tests')
    ->in(__DIR__.'/apps/Backoffice/src')
    ->in(__DIR__.'/tests')
    ->in(__DIR__.'/src')
;

$config = new PhpCsFixer\Config();
$config
    ->setRiskyAllowed(true)
    ->setCacheFile(__DIR__ . '/.php-cs-fixer.cache')
    ->setRules([
        '@PHP71Migration' => true,
        '@PHP71Migration:risky' => true,
        '@PHPUnit75Migration:risky' => true,
        '@PhpCsFixer' => true,
        '@PhpCsFixer:risky' => true,
        'array_syntax' => [
            'syntax' => 'short',
        ],
        'binary_operator_spaces' => [
            'default' => 'align_single_space_minimal',
        ],
        'concat_space' => [
            'spacing' => 'one',
        ],
        'constant_case' => true,
        'global_namespace_import' => [
            'import_classes' => true,
            'import_constants' => true,
            'import_functions' => true
        ],
        'lowercase_keywords' => true,
        'magic_constant_casing' => true,
        'magic_method_casing' => true,
        'native_function_casing' => true,
        'native_function_invocation' => false,
        'modernize_strpos' => true, // needs PHP 8+ or polyfill
        'multiline_whitespace_before_semicolons' => [
            'strategy' => 'new_line_for_chained_calls',
        ],
        'no_trailing_comma_in_singleline' => true,
        'no_whitespace_before_comma_in_array' => true,
        'normalize_index_brace' => true,
        'phpdoc_separation' =>  [
            'groups' => [
                ['BeforeSuite', 'AfterSuite', 'BeforeScenario', 'AfterScenario'],
                ['noinspection', 'phpstan-ignore-next-line'],
                ['deprecated', 'link', 'see', 'since'],
                ['author', 'copyright', 'license'],
                ['category', 'package', 'subpackage'],
                ['property', 'property-read', 'property-write'],
                ['internal', 'covers', 'coversNothing'],
                ['return'],
                ['throw'],
                ['param'],
            ],
        ],
        'phpdoc_to_comment' => [
            'ignored_tags' => [
                'noinspection',
                'phpstan-ignore-next-line',
                'var',
            ],
        ],
        'php_unit_test_case_static_method_calls' => [
            'call_type' => 'self',
        ],
        'single_line_comment_style' => [
            'comment_types' => ['hash'],
        ],
        'trailing_comma_in_multiline' => true,
        'trim_array_spaces' => true,
        'whitespace_after_comma_in_array' => true,
    ])
    ->setFinder($finder)
;

return $config;
