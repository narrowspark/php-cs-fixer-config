<?php

declare(strict_types=1);

namespace Narrowspark\CS\Config;

use PhpCsFixer\Config as CsConfig;
use PhpCsFixerCustomFixers\Fixer\CommentSurroundedBySpacesFixer;
use PhpCsFixerCustomFixers\Fixer\DataProviderNameFixer;
use PhpCsFixerCustomFixers\Fixer\DataProviderReturnTypeFixer;
use PhpCsFixerCustomFixers\Fixer\DataProviderStaticFixer;
use PhpCsFixerCustomFixers\Fixer\InternalClassCasingFixer;
use PhpCsFixerCustomFixers\Fixer\MultilineCommentOpeningClosingAloneFixer;
use PhpCsFixerCustomFixers\Fixer\NoCommentedOutCodeFixer;
use PhpCsFixerCustomFixers\Fixer\NoDoctrineMigrationsGeneratedCommentFixer;
use PhpCsFixerCustomFixers\Fixer\NoDuplicatedImportsFixer;
use PhpCsFixerCustomFixers\Fixer\NoImportFromGlobalNamespaceFixer;
use PhpCsFixerCustomFixers\Fixer\NoLeadingSlashInGlobalNamespaceFixer;
use PhpCsFixerCustomFixers\Fixer\NoNullableBooleanTypeFixer;
use PhpCsFixerCustomFixers\Fixer\NoPhpStormGeneratedCommentFixer;
use PhpCsFixerCustomFixers\Fixer\NoReferenceInFunctionDefinitionFixer;
use PhpCsFixerCustomFixers\Fixer\NoSuperfluousConcatenationFixer;
use PhpCsFixerCustomFixers\Fixer\NoUselessCommentFixer;
use PhpCsFixerCustomFixers\Fixer\NoUselessDoctrineRepositoryCommentFixer;
use PhpCsFixerCustomFixers\Fixer\NoUselessSprintfFixer;
use PhpCsFixerCustomFixers\Fixer\OperatorLinebreakFixer;
use PhpCsFixerCustomFixers\Fixer\PhpdocNoIncorrectVarAnnotationFixer;
use PhpCsFixerCustomFixers\Fixer\PhpdocNoSuperfluousParamFixer;
use PhpCsFixerCustomFixers\Fixer\PhpdocParamOrderFixer;
use PhpCsFixerCustomFixers\Fixer\PhpdocParamTypeFixer;
use PhpCsFixerCustomFixers\Fixer\PhpdocSelfAccessorFixer;
use PhpCsFixerCustomFixers\Fixer\PhpdocSingleLineVarFixer;
use PhpCsFixerCustomFixers\Fixer\PhpdocTypesTrimFixer;
use PhpCsFixerCustomFixers\Fixer\PhpUnitNoUselessReturnFixer;
use PhpCsFixerCustomFixers\Fixer\SingleSpaceAfterStatementFixer;
use PhpCsFixerCustomFixers\Fixer\SingleSpaceBeforeStatementFixer;
use const PHP_VERSION_ID;
use function array_merge;
use function is_string;
use function trim;

final class Config extends CsConfig
{
    /**
     * A list of override rules.
     *
     * @var array<string, bool|string|array<string, mixed>>
     */
    private $overwriteRules;

    /** @var array<string, array<string, string>> */
    private $headerRules = [];

    /**
     * Create new Config instance.
     *
     * @param null|string                                     $header
     * @param array<string, bool|string|array<string, mixed>> $overwriteConfig
     */
    public function __construct(?string $header = null, array $overwriteConfig = [])
    {
        parent::__construct('narrowspark');

        if (is_string($header)) {
            $this->headerRules['header_comment'] = [
                'comment_type' => 'PHPDoc',
                'header' => trim($header),
                'location' => 'after_declare_strict',
                'separate' => 'both',
            ];
        }

        $this->setRiskyAllowed(true);
        $this->registerCustomFixers(new \PedroTroller\CS\Fixer\Fixers());
        $this->registerCustomFixers(new \PhpCsFixerCustomFixers\Fixers());

        $this->overwriteRules = $overwriteConfig;
    }

    /**
     * @return array<int|string, mixed>
     */
    public function getRules(): array
    {
        return array_merge(
            $this->getNoGroupRules(),
            $this->getContribRules(),
            $this->getPhp71Rules(),
            PHP_VERSION_ID >= 70300 ? $this->getPhp73Rules() : [],
            $this->getSymfonyRules(),
            $this->getPsr12Rules(),
            $this->getPHPUnitRules(),
            $this->getPedroTrollerRules(),
            $this->getKubawerlosRules(),
            $this->headerRules,
            $this->overwriteRules
        );
    }

    /**
     * @return array<string, array<string, int|true>|bool>
     */
    protected function getPedroTrollerRules(): array
    {
        return [
            'PedroTroller/comment_line_to_phpdoc_block' => false,
            'PedroTroller/exceptions_punctuation' => true,
            'PedroTroller/forbidden_functions' => false,
            'PedroTroller/ordered_with_getter_and_setter_first' => true,
            'PedroTroller/line_break_between_method_arguments' => [
                'max-args' => 4,
                'max-length' => 120,
                'automatic-argument-merge' => true,
            ],
            'PedroTroller/line_break_between_statements' => true,
            'PedroTroller/useless_code_after_return' => false,
            'PedroTroller/phpspec' => false,
        ];
    }

    /**
     * @return array<string, bool>
     */
    protected function getKubawerlosRules(): array
    {
        return [
            InternalClassCasingFixer::name() => true,
            MultilineCommentOpeningClosingAloneFixer::name() => false,
            NoCommentedOutCodeFixer::name() => true,
            NoDoctrineMigrationsGeneratedCommentFixer::name() => true,
            NoImportFromGlobalNamespaceFixer::name() => false,
            NoLeadingSlashInGlobalNamespaceFixer::name() => true,
            NoNullableBooleanTypeFixer::name() => false,
            NoPhpStormGeneratedCommentFixer::name() => true,
            NoReferenceInFunctionDefinitionFixer::name() => false,
            NoSuperfluousConcatenationFixer::name() => true,
            NoUselessCommentFixer::name() => false,
            NoUselessDoctrineRepositoryCommentFixer::name() => true,
            OperatorLinebreakFixer::name() => true,
            PhpdocNoIncorrectVarAnnotationFixer::name() => true,
            PhpdocNoSuperfluousParamFixer::name() => true,
            PhpdocParamOrderFixer::name() => true,
            PhpdocParamTypeFixer::name() => true,
            PhpdocSelfAccessorFixer::name() => true,
            PhpdocSingleLineVarFixer::name() => true,
            SingleSpaceAfterStatementFixer::name() => true,
            SingleSpaceBeforeStatementFixer::name() => true,
            DataProviderNameFixer::name() => true,
            NoUselessSprintfFixer::name() => true,
            PhpUnitNoUselessReturnFixer::name() => true,
            NoDuplicatedImportsFixer::name() => true,
            DataProviderReturnTypeFixer::name() => true,
            CommentSurroundedBySpacesFixer::name() => true,
            DataProviderStaticFixer::name() => true,
            PhpdocTypesTrimFixer::name() => true,
        ];
    }

    /**
     * @return array<string, array<string, array<int, string>|bool|string>|bool>
     */
    protected function getPhp71Rules(): array
    {
        return [
            'combine_nested_dirname' => true,
            'list_syntax' => [
                'syntax' => 'short',
            ],
            'pow_to_exponentiation' => true,
            'random_api_migration' => true,
            'return_assignment' => true,
            'visibility_required' => [
                'elements' => [
                    'const',
                    'method',
                    'property',
                ],
            ],
            'void_return' => true,
        ];
    }

    /**
     * @return array<string, bool>
     */
    protected function getPhp73Rules(): array
    {
        return [
            'heredoc_indentation' => true,
        ];
    }

    /**
     * @return array<string, array<string, array<int, string>|bool|string>|bool>
     */
    protected function getContribRules(): array
    {
        return [
            '@DoctrineAnnotation' => true,
            'align_multiline_comment' => [
                'comment_type' => 'all_multiline',
            ],
            'no_binary_string' => true,
            'no_unset_on_property' => false,
            'array_indentation' => true,
            'array_syntax' => [
                'syntax' => 'short',
            ],
            'logical_operators' => true,
            'pre_increment' => false,
            'backtick_to_shell_exec' => true,
            'blank_line_before_return' => true,
            'class_keyword_remove' => false,
            'combine_consecutive_issets' => true,
            'combine_consecutive_unsets' => true,
            'comment_to_phpdoc' => false,
            'compact_nullable_typehint' => true,
            'date_time_immutable' => false,
            'declare_strict_types' => true,
            'dir_constant' => true,
            'ereg_to_preg' => true,
            'escape_implicit_backslashes' => true,
            'explicit_indirect_variable' => true,
            'explicit_string_variable' => true,
            'final_class' => true,
            'final_internal_class' => true,
            'fully_qualified_strict_types' => true,
            'general_phpdoc_annotation_remove' => false,
            'hash_to_slash_comment' => true,
            'header_comment' => false,
            'linebreak_after_opening_tag' => true,
            'magic_constant_casing' => true,
            'mb_str_functions' => false,
            'method_argument_space' => [
                'ensure_fully_multiline' => true,
                'keep_multiple_spaces_after_comma' => false,
            ],
            'static_lambda' => true,
            'string_line_ending' => true,
            'method_chaining_indentation' => true,
            'modernize_types_casting' => true,
            'multiline_comment_opening_closing' => true,
            'multiline_whitespace_before_semicolons' => [
                'strategy' => 'no_multi_line',
            ],
            'no_alternative_syntax' => true,
            'no_blank_lines_before_namespace' => false,
            'no_multiline_whitespace_before_semicolons' => false,
            'no_php4_constructor' => false,
            'no_short_echo_tag' => true,
            'no_useless_else' => true,
            'no_useless_return' => true,
            'no_superfluous_elseif' => true,
            'phpdoc_to_return_type' => true,
            'phpdoc_var_annotation_correct_order' => true,
            'no_superfluous_phpdoc_tags' => false,
            'not_operator_with_space' => false,
            'not_operator_with_successor_space' => true,
            'no_homoglyph_names' => false,
            'no_unset_cast' => true,
            'ordered_interfaces' => true,
            'php_unit_strict' => false,
            'php_unit_method_casing' => true,
            'php_unit_test_class_requires_covers' => false,
            'phpdoc_add_missing_param_annotation' => [
                'only_untyped' => false,
            ],
            'phpdoc_order' => true,
            'phpdoc_types_order' => [
                'null_adjustment' => 'always_first',
                'sort_algorithm' => 'alpha',
            ],
            'protected_to_private' => true,
            'psr0' => false,
            'psr4' => true,
            'semicolon_after_instruction' => true,
            'simplified_null_return' => false,
            'strict_comparison' => true,
            'strict_param' => true,
        ];
    }

    /**
     * @return array<string, array<string, array<int, string>|bool|string>|bool>
     */
    protected function getPsr12Rules(): array
    {
        return [
            '@PSR2' => true,
            'binary_operator_spaces' => true, // Symfony Rule
            'blank_line_after_opening_tag' => true, // Symfony Rule
            'braces' => [
                'allow_single_line_closure' => false,
                'position_after_anonymous_constructs' => 'same',
                'position_after_control_structures' => 'same',
                'position_after_functions_and_oop_constructs' => 'next',
            ],
            'concat_space' => ['spacing' => 'one'],
            'declare_equal_normalize' => [
                'space' => 'none',
            ], // Symfony Rule
            'lowercase_cast' => true,
            'new_with_braces' => true,
            'no_blank_lines_after_class_opening' => true,
            'no_extra_blank_lines' => false, // Symfony Rule
            'no_leading_import_slash' => true,
            'no_singleline_whitespace_before_semicolons' => true,
            'no_trailing_whitespace' => true,
            'no_whitespace_before_comma_in_array' => true,
            'ordered_class_elements' => [
                'order' => ['use_trait'],
            ], // Contrib
            'ordered_imports' => [
                'importsOrder' => [
                    'class',
                    'const',
                    'function',
                ],
            ],  // Contrib
            'return_type_declaration' => true,
            'short_scalar_cast' => true,
            'single_import_per_statement' => false,
            'space_after_semicolon' => [
                'remove_in_empty_for_expressions' => true,
            ], // Symfony Rule
            'ternary_operator_spaces' => true,
            'unary_operator_spaces' => true,
            'visibility_required' => [
                'elements' => ['const', 'method', 'property'],
            ],
            'whitespace_after_comma_in_array' => true,
        ];
    }

    /**
     * @return array<string, array<string, array<int, string>|bool|string>|bool>
     */
    protected function getPHPUnitRules(): array
    {
        return [
            'php_unit_expectation' => [
                'target' => 'newest',
            ],
            'php_unit_dedicate_assert_internal_type' => true,
            'php_unit_mock' => true,
            'php_unit_mock_short_will_return' => true,
            'php_unit_namespaced' => [
                'target' => 'newest',
            ],
            'php_unit_no_expectation_annotation' => [
                'target' => 'newest',
                'use_class_const' => true,
            ],
            'php_unit_test_case_static_method_calls' => [
                'call_type' => 'self',
            ],
            'php_unit_internal_class' => [
                'types' => [
                    'abstract',
                    'final',
                    'normal',
                ],
            ],
            'php_unit_ordered_covers' => true,
            'php_unit_set_up_tear_down_visibility' => true,
            'php_unit_strict' => false,
            'php_unit_size_class' => true,
            'php_unit_test_annotation' => true,
            'php_unit_test_class_requires_covers' => false,
        ];
    }

    /**
     * @return array<string, array<string, array<int, string>|bool|string>|bool>
     */
    protected function getSymfonyRules(): array
    {
        return [
            'set_type_to_cast' => true,
            'lowercase_static_reference' => true,
            'native_constant_invocation' => true,
            'blank_line_before_statement' => [
                'statements' => [
                    'break',
                    'continue',
                    'declare',
                    'default',
                    'die',
                    'do',
                    'exit',
                    'for',
                    'foreach',
                    'goto',
                    'if',
                    'include',
                    'include_once',
                    'require',
                    'require_once',
                    'return',
                    'switch',
                    'throw',
                    'try',
                    'while',
                    'yield',
                ],
            ],
            'yoda_style' => false,
            'cast_spaces' => true,
            'class_attributes_separation' => [
                'elements' => [
                    'method',
                    'property',
                ],
            ],
            'error_suppression' => [
                'mute_deprecation_error' => true,
                'noise_remaining_usages' => false,
            ],
            'standardize_increment' => true,
            'concat_space' => [
                'spacing' => 'one',
            ],
            'doctrine_annotation_array_assignment' => [
                'operator' => ':',
            ],
            'doctrine_annotation_braces' => [
                'syntax' => 'without_braces',
            ],
            'doctrine_annotation_indentation' => true,
            'doctrine_annotation_spaces' => [
                'after_argument_assignments' => false,
                'after_array_assignments_colon' => true,
                'after_array_assignments_equals' => false,
                'around_parentheses' => true,
                'before_argument_assignments' => false,
                'before_array_assignments_colon' => false,
                'before_array_assignments_equals' => false,
            ],
            'function_to_constant' => true,
            'function_typehint_space' => true,
            'fopen_flags' => false,
            'fopen_flag_order' => false,
            'heredoc_to_nowdoc' => true,
            'is_null' => true,
            'implode_call' => true,
            'include' => true,
            'increment_style' => [
                'style' => 'post',
            ],
            'no_unneeded_curly_braces' => true,
            'no_unneeded_final_method' => true,
            'non_printable_character' => true,
            'lowercase_cast' => true,
            'magic_method_casing' => true,
            'method_separation' => false,
            'native_function_casing' => true,
            'native_function_invocation' => true,
            'new_with_braces' => true,
            'native_function_type_declaration_casing' => true,
            'no_alias_functions' => true,
            'no_blank_lines_after_class_opening' => true,
            'no_blank_lines_after_phpdoc' => true,
            'no_empty_comment' => true,
            'no_empty_phpdoc' => true,
            'no_empty_statement' => true,
            'no_extra_consecutive_blank_lines' => false,
            'no_null_property_initialization' => true,
            'no_leading_import_slash' => true,
            'no_leading_namespace_whitespace' => false,
            'no_mixed_echo_print' => [
                'use' => 'echo',
            ],
            'no_multiline_whitespace_around_double_arrow' => true,
            'no_short_bool_cast' => true,
            'no_singleline_whitespace_before_semicolons' => true,
            'no_spaces_around_offset' => true,
            'no_trailing_comma_in_list_call' => true,
            'no_trailing_comma_in_singleline_array' => true,
            'no_unneeded_control_parentheses' => true,
            'no_unreachable_default_argument_value' => true,
            'no_unused_imports' => true,
            'no_whitespace_before_comma_in_array' => true,
            'no_whitespace_in_blank_line' => true,
            'normalize_index_brace' => true,
            'object_operator_without_whitespace' => true,
            'php_unit_construct' => true,
            'php_unit_dedicate_assert' => true,
            'php_unit_fqcn_annotation' => true,
            'phpdoc_align' => true,
            'phpdoc_annotation_without_dot' => true,
            'phpdoc_indent' => true,
            'phpdoc_inline_tag' => true,
            'phpdoc_no_access' => true,
            'phpdoc_no_alias_tag' => true,
            'phpdoc_no_empty_return' => false,
            'phpdoc_no_package' => true,
            'phpdoc_no_useless_inheritdoc' => true,
            'phpdoc_return_self_reference' => true,
            'phpdoc_scalar' => true,
            'phpdoc_separation' => true,
            'phpdoc_single_line_var_spacing' => true,
            'phpdoc_summary' => true,
            'phpdoc_to_comment' => false,
            'phpdoc_trim' => true,
            'phpdoc_types' => true,
            'phpdoc_var_without_name' => true,
            'phpdoc_trim_consecutive_blank_line_separation' => true,
            'return_type_declaration' => true,
            'self_accessor' => false,
            'short_scalar_cast' => true,
            'silenced_deprecation_error' => false,
            'simple_to_complex_string_variable' => true,
            'single_blank_line_before_namespace' => true,
            'single_quote' => true,
            'single_trait_insert_per_statement' => true,
            'single_line_comment_style' => false,
            'single_line_throw' => true,
            'standardize_not_equals' => true,
            'ternary_operator_spaces' => true,
            'ternary_to_null_coalescing' => true,
            'trailing_comma_in_multiline_array' => true,
            'trim_array_spaces' => true,
            'unary_operator_spaces' => true,
            'whitespace_after_comma_in_array' => true,
        ];
    }

    /**
     * @return array<string, array<string, array<int, string>|bool|string>|bool>
     */
    public function getNoGroupRules(): array
    {
        return [
            'final_static_access' => true,
            'final_public_method_for_abstract_class' => false,
            'lowercase_constants' => false,
            'global_namespace_import' => [
                'import_classes' => true,
                'import_constants' => true,
                'import_functions' => true,
            ],
            'nullable_type_declaration_for_default_null_value' => true,
            'phpdoc_line_span' => [
                'const' => 'multi',
                'method' => 'multi',
                'property' => 'multi',
            ],
            'phpdoc_to_param_type' => false,
            'self_static_accessor' => true,
        ];
    }
}
