<?php

$finder = PhpCsFixer\Finder::create()
    ->in([__DIR__ . '/src', __DIR__ . '/tests']);

return (new PhpCsFixer\Config())
    ->setRiskyAllowed(false)
    ->setUnsupportedPhpVersionAllowed(true)
    ->setFinder($finder)
    ->setRules([
        'array_syntax'               => ['syntax' => 'short'],
        'list_syntax'                => ['syntax' => 'short'],
        'ternary_to_null_coalescing' => true,
        'no_unused_imports'          => true,
        'no_empty_statement'         => true,
        'no_trailing_whitespace'     => true,
        'single_blank_line_at_eof'   => true,
    ]);
