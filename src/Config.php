<?php

declare(strict_types=1);

/**
 * Copyright (c) 2015-2020 Daniel Bannert
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/narrowspark/php-cs-fixer-config
 */

namespace Narrowspark\CS\Config;

use PedroTroller\CS\Fixer\ClassNotation\OrderedWithGetterAndSetterFirstFixer;
use PedroTroller\CS\Fixer\CodingStyle\ExceptionsPunctuationFixer;
use PedroTroller\CS\Fixer\CodingStyle\ForbiddenFunctionsFixer;
use PedroTroller\CS\Fixer\CodingStyle\LineBreakBetweenMethodArgumentsFixer;
use PedroTroller\CS\Fixer\CodingStyle\LineBreakBetweenStatementsFixer;
use PedroTroller\CS\Fixer\Comment\CommentLineToPhpdocBlockFixer;
use PedroTroller\CS\Fixer\Comment\SingleLineCommentFixer;
use PedroTroller\CS\Fixer\Comment\UselessCommentFixer;
use PedroTroller\CS\Fixer\DeadCode\UselessCodeAfterReturnFixer;
use PedroTroller\CS\Fixer\DoctrineMigrationsFixer;
use PedroTroller\CS\Fixer\Phpspec\OrderedSpecElementsFixer;
use PedroTroller\CS\Fixer\Phpspec\PhpspecScenarioReturnTypeDeclarationFixer;
use PedroTroller\CS\Fixer\Phpspec\PhpspecScenarioScopeFixer;
use PedroTroller\CS\Fixer\PhpspecFixer;
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
use PhpCsFixerCustomFixers\Fixer\PhpdocOnlyAllowedAnnotationsFixer;
use PhpCsFixerCustomFixers\Fixer\PhpdocParamOrderFixer;
use PhpCsFixerCustomFixers\Fixer\PhpdocParamTypeFixer;
use PhpCsFixerCustomFixers\Fixer\PhpdocSelfAccessorFixer;
use PhpCsFixerCustomFixers\Fixer\PhpdocSingleLineVarFixer;
use PhpCsFixerCustomFixers\Fixer\PhpdocTypesTrimFixer;
use PhpCsFixerCustomFixers\Fixer\PhpUnitNoUselessReturnFixer;
use PhpCsFixerCustomFixers\Fixer\SingleSpaceAfterStatementFixer;
use PhpCsFixerCustomFixers\Fixer\SingleSpaceBeforeStatementFixer;
use function array_merge;
use function is_string;
use function trim;

final class Config extends CsConfig
{
    public const VERSION = '';

    /**
     * A list of override rules.
     *
     * @var array<string, array<string, mixed>|bool|string>
     */
    private $overwriteRules;

    /** @var array<string, array<string, string>> */
    private $headerRules = [];

    /**
     * Create new Config instance.
     *
     * @param array<string, array<string, mixed>|bool|string> $overwriteConfig
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

        // pedrotroller/php-cs-custom-fixer
        $this->registerCustomFixers([
            new OrderedWithGetterAndSetterFirstFixer(),
            new ExceptionsPunctuationFixer(),
            new ForbiddenFunctionsFixer(),
            new LineBreakBetweenMethodArgumentsFixer(),
            new LineBreakBetweenStatementsFixer(),
            new CommentLineToPhpdocBlockFixer(),
            new SingleLineCommentFixer(),
            new UselessCommentFixer(),
            new UselessCodeAfterReturnFixer(),
            new OrderedSpecElementsFixer(),
            new PhpspecScenarioReturnTypeDeclarationFixer(),
            new PhpspecScenarioScopeFixer(),
            new DoctrineMigrationsFixer(),
            new PhpspecFixer(),
        ]);
        // kubawerlos/php-cs-fixer-custom-fixers
        $this->registerCustomFixers([
            new InternalClassCasingFixer(),
            new MultilineCommentOpeningClosingAloneFixer(),
            new NoCommentedOutCodeFixer(),
            new NoDoctrineMigrationsGeneratedCommentFixer(),
            new NoImportFromGlobalNamespaceFixer(),
            new NoLeadingSlashInGlobalNamespaceFixer(),
            new NoNullableBooleanTypeFixer(),
            new NoPhpStormGeneratedCommentFixer(),
            new NoReferenceInFunctionDefinitionFixer(),
            new NoSuperfluousConcatenationFixer(),
            new NoUselessCommentFixer(),
            new NoUselessDoctrineRepositoryCommentFixer(),
            new OperatorLinebreakFixer(),
            new PhpdocNoIncorrectVarAnnotationFixer(),
            new PhpdocNoSuperfluousParamFixer(),
            new PhpdocParamOrderFixer(),
            new PhpdocParamTypeFixer(),
            new PhpdocOnlyAllowedAnnotationsFixer(),
            new PhpdocSelfAccessorFixer(),
            new PhpdocSingleLineVarFixer(),
            new SingleSpaceAfterStatementFixer(),
            new SingleSpaceBeforeStatementFixer(),
            new DataProviderNameFixer(),
            new NoUselessSprintfFixer(),
            new PhpUnitNoUselessReturnFixer(),
            new NoDuplicatedImportsFixer(),
            new DataProviderReturnTypeFixer(),
            new CommentSurroundedBySpacesFixer(),
            new DataProviderStaticFixer(),
            new PhpdocTypesTrimFixer(),
        ]);

        $this->overwriteRules = $overwriteConfig;
    }

    /**
     * @return ((array|bool|int|mixed|string)[]|bool|string)[]
     *
     * @psalm-return array<int|string, array|bool|string>
     */
    public function getRules(): array
    {
        return array_merge(
            $this->getNoGroupRules(),
            $this->getContribRules(),
            $this->getPhp71Rules(),
            $this->getPhp73Rules(),
            $this->getSymfonyRules(),
            $this->getPsr2Rules(),
            $this->getPsr12Rules(),
            $this->getPHPUnitRules(),
            $this->getPedroTrollerRules(),
            $this->getKubawerlosRules(),
            $this->headerRules,
            $this->overwriteRules
        );
    }

    /**
     * @return ((string|true)[]|bool)[]
     *
     * @psalm-return array{final_static_access: true, final_public_method_for_abstract_class: true, lowercase_constants: false, global_namespace_import: array{import_classes: true, import_constants: true, import_functions: true}, nullable_type_declaration_for_default_null_value: true, phpdoc_line_span: array{const: string, method: string, property: string}, phpdoc_to_param_type: false, self_static_accessor: true}
     */
    public function getNoGroupRules(): array
    {
        return [
            'final_static_access' => true,
            'final_public_method_for_abstract_class' => true,
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

    /**
     * @return ((int|true)[]|bool)[]
     *
     * @psalm-return array{PedroTroller/comment_line_to_phpdoc_block: true, PedroTroller/exceptions_punctuation: true, PedroTroller/forbidden_functions: false, PedroTroller/ordered_with_getter_and_setter_first: true, PedroTroller/line_break_between_method_arguments: array{max-args: int, max-length: int, automatic-argument-merge: true}, PedroTroller/line_break_between_statements: true, PedroTroller/useless_code_after_return: true, PedroTroller/phpspec: false, PedroTroller/doctrine_migrations: true}
     */
    public function getPedroTrollerRules(): array
    {
        return [
            'PedroTroller/comment_line_to_phpdoc_block' => true,
            'PedroTroller/exceptions_punctuation' => true,
            'PedroTroller/forbidden_functions' => false,
            'PedroTroller/ordered_with_getter_and_setter_first' => true,
            'PedroTroller/line_break_between_method_arguments' => [
                'max-args' => 4,
                'max-length' => 120,
                'automatic-argument-merge' => true,
            ],
            'PedroTroller/line_break_between_statements' => true,
            'PedroTroller/useless_code_after_return' => true,
            'PedroTroller/phpspec' => false,
            'PedroTroller/doctrine_migrations' => true,
        ];
    }

    /**
     * @return bool[]
     *
     * @psalm-return array<string, bool>
     */
    public function getKubawerlosRules(): array
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
            PhpdocOnlyAllowedAnnotationsFixer::name() => false,
        ];
    }

    /**
     * @return ((string|string[])[]|true)[]
     *
     * @psalm-return array{combine_nested_dirname: true, list_syntax: array{syntax: string}, pow_to_exponentiation: true, random_api_migration: true, return_assignment: true, visibility_required: array{elements: array{0: string, 1: string, 2: string}}, void_return: true}
     */
    public function getPhp71Rules(): array
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
     * @return true[]
     *
     * @psalm-return array{heredoc_indentation: true}
     */
    public function getPhp73Rules(): array
    {
        return [
            'heredoc_indentation' => true,
        ];
    }

    /**
     * @return ((bool|string|string[])[]|bool)[]
     *
     * @psalm-return array{@DoctrineAnnotation: true, align_multiline_comment: array{comment_type: string}, no_binary_string: true, no_unset_on_property: false, array_indentation: true, array_syntax: array{syntax: string}, logical_operators: true, pre_increment: false, backtick_to_shell_exec: true, blank_line_before_return: true, class_keyword_remove: false, combine_consecutive_issets: true, combine_consecutive_unsets: true, comment_to_phpdoc: false, compact_nullable_typehint: true, date_time_immutable: true, declare_strict_types: true, dir_constant: true, ereg_to_preg: true, escape_implicit_backslashes: true, explicit_indirect_variable: true, explicit_string_variable: true, ordered_class_elements: true, ordered_imports: array{importsOrder: array{0: string, 1: string, 2: string}}, final_class: true, final_internal_class: true, fully_qualified_strict_types: true, general_phpdoc_annotation_remove: false, hash_to_slash_comment: true, header_comment: false, linebreak_after_opening_tag: true, magic_constant_casing: true, mb_str_functions: false, method_argument_space: array{ensure_fully_multiline: true, keep_multiple_spaces_after_comma: false}, static_lambda: true, string_line_ending: true, method_chaining_indentation: true, modernize_types_casting: true, multiline_comment_opening_closing: true, multiline_whitespace_before_semicolons: array{strategy: string}, no_alternative_syntax: true, no_blank_lines_before_namespace: false, no_multiline_whitespace_before_semicolons: false, no_php4_constructor: false, no_short_echo_tag: true, no_useless_else: true, no_useless_return: true, no_superfluous_elseif: true, phpdoc_to_return_type: true, phpdoc_var_annotation_correct_order: true, no_superfluous_phpdoc_tags: true, not_operator_with_space: false, not_operator_with_successor_space: true, no_homoglyph_names: false, no_unset_cast: true, ordered_interfaces: true, phpdoc_add_missing_param_annotation: array{only_untyped: true}, phpdoc_order: true, phpdoc_types_order: array{null_adjustment: string, sort_algorithm: string}, protected_to_private: true, psr0: false, psr4: true, semicolon_after_instruction: true, simplified_null_return: false, strict_comparison: true, strict_param: true}
     */
    public function getContribRules(): array
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
            'date_time_immutable' => true,
            'declare_strict_types' => true,
            'dir_constant' => true,
            'ereg_to_preg' => true,
            'escape_implicit_backslashes' => true,
            'explicit_indirect_variable' => true,
            'explicit_string_variable' => true,
            'ordered_class_elements' => true,
            'ordered_imports' => [
                'importsOrder' => [
                    'class',
                    'const',
                    'function',
                ],
            ],
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
            'no_superfluous_phpdoc_tags' => true,
            'not_operator_with_space' => false,
            'not_operator_with_successor_space' => true,
            'no_homoglyph_names' => false,
            'no_unset_cast' => true,
            'ordered_interfaces' => true,
            'phpdoc_add_missing_param_annotation' => [
                'only_untyped' => true,
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
     * @return true[]
     *
     * @psalm-return array{@PSR2: true}
     */
    public function getPsr2Rules(): array
    {
        return [
            '@PSR2' => true,
        ];
    }

    /**
     * @return ((bool|string|string[])[]|bool)[]
     *
     * @psalm-return array{binary_operator_spaces: true, blank_line_after_opening_tag: true, braces: array{allow_single_line_closure: false, position_after_anonymous_constructs: string, position_after_control_structures: string, position_after_functions_and_oop_constructs: string}, concat_space: array{spacing: string}, declare_equal_normalize: array{space: string}, lowercase_cast: true, new_with_braces: true, no_blank_lines_after_class_opening: true, no_extra_blank_lines: array{tokens: array{0: string, 1: string, 2: string, 3: string, 4: string, 5: string, 6: string, 7: string, 8: string, 9: string, 10: string, 11: string, 12: string}}, no_leading_import_slash: true, no_singleline_whitespace_before_semicolons: true, no_trailing_whitespace: true, no_whitespace_before_comma_in_array: true, return_type_declaration: true, short_scalar_cast: true, single_import_per_statement: false, space_after_semicolon: array{remove_in_empty_for_expressions: true}, ternary_operator_spaces: true, unary_operator_spaces: true, visibility_required: array{elements: array{0: string, 1: string, 2: string}}, whitespace_after_comma_in_array: true}
     */
    public function getPsr12Rules(): array
    {
        return [
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
            'no_extra_blank_lines' => [
                'tokens' => [
                    'break',
                    'case',
                    'continue',
                    'curly_brace_block',
                    'default',
                    'extra',
                    'parenthesis_brace_block',
                    'return',
                    'square_brace_block',
                    'switch',
                    'throw',
                    'use',
                    'use_trait',
                ],
            ],
            'no_leading_import_slash' => true,
            'no_singleline_whitespace_before_semicolons' => true,
            'no_trailing_whitespace' => true,
            'no_whitespace_before_comma_in_array' => true,
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
     * @return ((string|string[]|true)[]|bool)[]
     *
     * @psalm-return array{php_unit_expectation: array{target: string}, php_unit_dedicate_assert_internal_type: true, php_unit_mock: true, php_unit_mock_short_will_return: true, php_unit_namespaced: array{target: string}, php_unit_no_expectation_annotation: array{target: string, use_class_const: true}, php_unit_test_case_static_method_calls: array{call_type: string}, php_unit_internal_class: array{types: array{0: string, 1: string, 2: string}}, php_unit_ordered_covers: true, php_unit_set_up_tear_down_visibility: true, php_unit_strict: false, php_unit_size_class: true, php_unit_test_annotation: true, php_unit_test_class_requires_covers: true, php_unit_method_casing: true, php_unit_construct: true, php_unit_dedicate_assert: true, php_unit_fqcn_annotation: true}
     */
    public function getPHPUnitRules(): array
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
            'php_unit_test_class_requires_covers' => true,
            'php_unit_method_casing' => true,
            'php_unit_construct' => true,
            'php_unit_dedicate_assert' => true,
            'php_unit_fqcn_annotation' => true,
        ];
    }

    /**
     * @return ((bool|string|string[])[]|bool)[]
     *
     * @psalm-return array{set_type_to_cast: true, lowercase_static_reference: true, native_constant_invocation: true, blank_line_before_statement: array{statements: array{0: string, 1: string, 2: string, 3: string, 4: string, 5: string, 6: string, 7: string, 8: string, 9: string, 10: string, 11: string, 12: string, 13: string, 14: string, 15: string, 16: string, 17: string, 18: string, 19: string, 20: string}}, yoda_style: false, cast_spaces: true, class_attributes_separation: array{elements: array{0: string, 1: string}}, error_suppression: array{mute_deprecation_error: true, noise_remaining_usages: false}, standardize_increment: true, concat_space: array{spacing: string}, doctrine_annotation_array_assignment: array{operator: string}, doctrine_annotation_braces: array{syntax: string}, doctrine_annotation_indentation: true, doctrine_annotation_spaces: array{after_argument_assignments: false, after_array_assignments_colon: true, after_array_assignments_equals: false, around_parentheses: true, before_argument_assignments: false, before_array_assignments_colon: false, before_array_assignments_equals: false}, function_to_constant: true, function_typehint_space: true, fopen_flags: true, fopen_flag_order: true, heredoc_to_nowdoc: true, is_null: true, implode_call: true, include: true, increment_style: array{style: string}, no_unneeded_curly_braces: true, no_unneeded_final_method: true, non_printable_character: true, lowercase_cast: true, magic_method_casing: true, method_separation: false, native_function_casing: true, native_function_invocation: true, new_with_braces: true, native_function_type_declaration_casing: true, no_alias_functions: true, no_blank_lines_after_class_opening: true, no_blank_lines_after_phpdoc: true, no_empty_comment: true, no_empty_phpdoc: true, no_empty_statement: true, no_extra_consecutive_blank_lines: false, no_null_property_initialization: true, no_leading_import_slash: true, no_leading_namespace_whitespace: false, no_mixed_echo_print: array{use: string}, no_multiline_whitespace_around_double_arrow: true, no_short_bool_cast: true, no_singleline_whitespace_before_semicolons: true, no_spaces_around_offset: true, no_trailing_comma_in_list_call: true, no_trailing_comma_in_singleline_array: true, no_unneeded_control_parentheses: true, no_unreachable_default_argument_value: true, no_unused_imports: true, no_whitespace_before_comma_in_array: true, no_whitespace_in_blank_line: true, normalize_index_brace: true, object_operator_without_whitespace: true, phpdoc_align: true, phpdoc_annotation_without_dot: true, phpdoc_indent: true, phpdoc_inline_tag: true, phpdoc_no_access: true, phpdoc_no_alias_tag: true, phpdoc_no_empty_return: false, phpdoc_no_package: true, phpdoc_no_useless_inheritdoc: true, phpdoc_return_self_reference: true, phpdoc_scalar: true, phpdoc_separation: true, phpdoc_single_line_var_spacing: true, phpdoc_summary: true, phpdoc_to_comment: false, phpdoc_trim: true, phpdoc_types: true, phpdoc_var_without_name: true, phpdoc_trim_consecutive_blank_line_separation: true, return_type_declaration: true, self_accessor: false, short_scalar_cast: true, silenced_deprecation_error: false, simple_to_complex_string_variable: true, single_blank_line_before_namespace: true, single_quote: true, single_trait_insert_per_statement: true, single_line_comment_style: false, single_line_throw: true, standardize_not_equals: true, ternary_operator_spaces: true, ternary_to_null_coalescing: true, trailing_comma_in_multiline_array: true, trim_array_spaces: true, unary_operator_spaces: true, whitespace_after_comma_in_array: true}
     */
    public function getSymfonyRules(): array
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
            'fopen_flags' => true,
            'fopen_flag_order' => true,
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
}
