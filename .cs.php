<?php
$finder = PhpCsFixer\Finder::create();
$finder->exclude("/vendor");
$finder->in(__DIR__ . '/app')
    ->in(__DIR__ . '/console')
    ->in(__DIR__ . '/config')
    ->in(__DIR__ . '/public')
    ->in(__DIR__ . '/tests')
    ->name('*.php')
    ->exclude("/vendor")
    ->ignoreDotFiles(true)
    ->ignoreVCS(true);

$config = new PhpCsFixer\Config();

$config->setUsingCache(false)
    ->setRiskyAllowed(false)
    //->setCacheFile(__DIR__ . '/.php_cs.cache')
    ->setRules([
        '@PSR1' => true,
        '@PSR2' => true,
        '@PSR12' => true,
        '@Symfony' => true,
        '@PhpCsFixer' => true,

        // custom rules
        'align_multiline_comment' => ['comment_type' => 'phpdocs_only'], // psr-5
        'phpdoc_to_comment' => false,
        'no_superfluous_phpdoc_tags' => false,
        'array_indentation' => true,
        'array_syntax' => ['syntax' => 'short'],
        'concat_space' => ['spacing' => 'one'],
        'compact_nullable_typehint' => true,
        'declare_equal_normalize' => ['space' => 'single'],
        'increment_style' => ['style' => 'post'],
        'list_syntax' => ['syntax' => 'short'],
        'phpdoc_add_missing_param_annotation' => ['only_untyped' => false],
        'phpdoc_align' => true,
        'phpdoc_no_empty_return' => false,
        'phpdoc_order' => true, // psr-5
        'phpdoc_no_useless_inheritdoc' => false,
        'protected_to_private' => false,
        'yoda_style' => false,
        'method_argument_space' => ['on_multiline' => 'ensure_fully_multiline'],
        'ordered_imports' => [
            'sort_algorithm' => 'alpha',
            'imports_order' => ['class', 'const', 'function']
        ],
        'single_line_throw' => false,

        //Array Notation
        'no_multiline_whitespace_around_double_arrow' => true,
        'no_trailing_comma_in_singleline_array' => true,
        'no_whitespace_before_comma_in_array' => true,
        'normalize_index_brace' => true,
        'trim_array_spaces' => true,
        'whitespace_after_comma_in_array' => true,

        //Basic
        'braces' => true,
        'encoding' => true,

        //Casing
        'constant_case' => true,
        'lowercase_keywords' => true,
        'lowercase_static_reference' => true,
        'magic_constant_casing' => true,
        'magic_method_casing' => true,
        'native_function_casing' => true,
        'native_function_type_declaration_casing' => true,

        //Cast Notation
        'cast_spaces' => ['space' => 'single'],

    ])->setFinder($finder);

return $config;

