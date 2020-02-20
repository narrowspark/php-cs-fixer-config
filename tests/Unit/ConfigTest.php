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

namespace Narrowspark\CS\Config\Tests\Unit;

use Exception;
use Generator;
use Narrowspark\CS\Config\Config;
use Narrowspark\TestingHelper\Traits\AssertArrayTrait;
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
use PhpCsFixer\ConfigInterface;
use PhpCsFixer\Fixer\FixerInterface;
use PhpCsFixer\FixerFactory;
use PhpCsFixer\RuleSet;
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
use PHPUnit\Framework\TestCase;
use function array_diff;
use function array_diff_key;
use function array_keys;
use function array_map;
use function array_merge;
use function count;
use function implode;
use function is_array;
use function is_string;
use function sprintf;
use function str_replace;
use function trim;
use function var_export;

/**
 * @internal
 *
 * @covers \Narrowspark\CS\Config\Config
 *
 * @medium
 */
final class ConfigTest extends TestCase
{
    use AssertArrayTrait;

    public function testImplementsInterface(): void
    {
        self::assertInstanceOf(ConfigInterface::class, new Config());
    }

    public function testValues(): void
    {
        $config = new Config();

        self::assertSame('narrowspark', $config->getName());
    }

    public function testIsRisky(): void
    {
        $config = new Config();

        self::assertTrue($config->getRiskyAllowed());
    }

    public function testGetCustomFixers(): void
    {
        $config = new Config();

        self::assertEquals([
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
        ], $config->getCustomFixers());
    }

    public function testGetPsr2Rules(): void
    {
        self::assertSame(
            $this->getPsr2Rules(),
            (new Config())->getPsr2Rules(),
        );
    }

    public function testGetSymfonyRules(): void
    {
        self::assertSame(
            $this->getSymfonyRules(),
            (new Config())->getSymfonyRules(),
        );
    }

    public function testGetContribRules(): void
    {
        self::assertSame(
            $this->getContribRules(),
            (new Config())->getContribRules(),
        );
    }

    public function testGetPsr12Rules(): void
    {
        self::assertSame(
            $this->getPsr12Rules(),
            (new Config())->getPsr12Rules(),
        );
    }

    public function testGetNoGroupRules(): void
    {
        self::assertSame(
            $this->getNoGroupRules(),
            (new Config())->getNoGroupRules(),
        );
    }

    public function testGetPhp71Rules(): void
    {
        self::assertSame(
            $this->getPhp71Rules(),
            (new Config())->getPhp71Rules(),
        );
    }

    public function testGetPhp73Rules(): void
    {
        self::assertSame(
            $this->getPhp73Rules(),
            (new Config())->getPhp73Rules(),
        );
    }

    public function testGetPHPUnitRules(): void
    {
        self::assertSame(
            $this->getPHPUnitRules(),
            (new Config())->getPHPUnitRules(),
        );
    }

    public function testGetPedroTrollerRules(): void
    {
        self::assertSame(
            $this->getPedroTrollerRules(),
            (new Config())->getPedroTrollerRules(),
        );
    }

    public function testGetKubawerlosRules(): void
    {
        self::assertSame(
            $this->getKubawerlosRules(),
            (new Config())->getKubawerlosRules(),
        );
    }

    public function testIfAllRulesAreTested(): void
    {
        $testRules = array_merge(
            $this->getNoGroupRules(),
            $this->getPsr2Rules(),
            $this->getPsr12Rules(),
            $this->getContribRules(),
            $this->getSymfonyRules(),
            $this->getPhp71Rules(),
            $this->getPhp73Rules(),
            $this->getPHPUnitRules(),
            $this->getPedroTrollerRules(),
            $this->getKubawerlosRules(),
        );

        /** @var \PedroTroller\CS\Fixer\AbstractFixer $fixer */
        foreach (new \PedroTroller\CS\Fixer\Fixers() as $fixer) {
            $testRules[$fixer->getName()] = true;
        }

        /** @var \PedroTroller\CS\Fixer\AbstractFixer $fixer */
        foreach (new \PhpCsFixerCustomFixers\Fixers() as $fixer) {
            $testRules[$fixer->getName()] = true;
        }

        foreach ($this->getDeprecatedFixer() as $depFixer) {
            unset($testRules[$depFixer]);
        }

        $rules = (new Config())->getRules();

        if (count($testRules) !== count($rules)) {
            $message = "Found missing keys in \\Narrowspark\\CS\\Config\\Config::getRules()\n";
            $message .= str_replace(['array (', ')', ','], '', var_export(array_keys(array_diff_key($testRules, $rules)), true));

            self::fail($message);
        } else {
            /** @psalm-suppress InternalMethod */
            $this->addToAssertionCount(1);
        }
    }

    public function testDoesNotHaveHeaderCommentFixerByDefault(): void
    {
        $rules = (new Config())->getRules();

        self::assertArrayHasKey('header_comment', $rules);
        self::assertFalse($rules['header_comment']);
        self::assertFalse($rules['no_blank_lines_before_namespace']);
        self::assertTrue($rules['single_blank_line_before_namespace']);
    }

    public function testHasHeaderCommentFixerIfProvided(): void
    {
        $header = 'foo';
        $config = new Config($header);
        $rules = $config->getRules();

        self::assertArrayHasKey('header_comment', $rules);

        $expected = [
            'comment_type' => 'PHPDoc',
            'header' => $header,
            'location' => 'after_declare_strict',
            'separate' => 'both',
        ];
        self::assertSame($expected, $rules['header_comment']);
        self::assertFalse($rules['no_blank_lines_before_namespace']);
        self::assertTrue($rules['single_blank_line_before_namespace']);
    }

    public function testAllConfiguredRulesAreBuiltIn(): void
    {
        $pedroTrollerRules = [];

        /** @var \PedroTroller\CS\Fixer\AbstractFixer $fixer */
        foreach (new \PedroTroller\CS\Fixer\Fixers() as $fixer) {
            if ($fixer->isDeprecated()) {
                continue;
            }

            $pedroTrollerRules[] = $fixer->getName();
        }

        $kubawerlosRules = [];

        foreach (new \PhpCsFixerCustomFixers\Fixers() as $fixer) {
            $kubawerlosRules[] = $fixer->getName();
        }

        $fixersNotBuiltIn = array_diff(
            $this->configuredFixers(),
            array_merge($this->builtInFixers(), $pedroTrollerRules, $kubawerlosRules)
        );

        self::assertEmpty($fixersNotBuiltIn, sprintf(
            'Failed to assert that fixers for the rules [%s] are built in',
            implode('", "', $fixersNotBuiltIn)
        ));
    }

    /**
     * @dataProvider provideDoesNotHaveRulesEnabledCases
     *
     * @param array<int|string, string>|string $reason
     *
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws Exception
     */
    public function testDoesNotHaveRulesEnabled(string $fixer, $reason): void
    {
        $config = new Config();
        $rule = [
            $fixer => false,
        ];

        if ($fixer === 'array_syntax' && is_array($reason)) {
            self::assertNotSame(['syntax' => 'long'], $config->getRules()['array_syntax'], sprintf(
                'Fixer [%s] should not be enabled, because [%s]',
                $fixer,
                $reason['long']
            ));
        } elseif (is_string($reason)) {
            self::assertArraySubset($rule, $config->getRules(), true, sprintf(
                'Fixer [%s] should not be enabled, because [%s]',
                $fixer,
                $reason
            ));
        } else {
            self::fail($fixer);
        }
    }

    /**
     * @return array<int, array<string, string>|string>
     *
     * @psalm-return list<array{0: string, 1: array{long: string}|string}>
     */
    public static function provideDoesNotHaveRulesEnabledCases(): iterable
    {
        $symfonyFixers = [
            'self_accessor' => 'it causes an edge case error',
        ];

        $contribFixers = [
            'header_comment' => 'it is not enabled by default',
            'array_syntax' => [
                'long' => 'it conflicts with short array syntax (which is enabled)',
            ],
            'no_php4_constructor' => 'it changes behaviour',
            'not_operator_with_space' => 'we do not need leading and trailing whitespace before !',
            'php_unit_strict' => 'it changes behaviour',
            'psr0' => 'we are using PSR-4',
            'no_homoglyph_names' => 'renames classes and cannot rename the files. You might have string references to renamed code (``$$name``)',
            'simplified_null_return' => 'it changes behaviour on void return',
        ];

        $php73Fixers = [
            'heredoc_indentation' => 'Is destroying Heredoc/nowdoc',
        ];

        $fixers = array_merge($contribFixers, $symfonyFixers, $php73Fixers);

        $data = [];

        foreach ($fixers as $fixer => $reason) {
            $data[] = [
                $fixer,
                $reason,
            ];
        }

        return $data;
    }

    public function testHeaderCommentFixerIsDisabledByDefault(): void
    {
        $rules = (new Config())->getRules();

        self::assertArrayHasKey('header_comment', $rules);
        self::assertFalse($rules['header_comment']);
    }

    /**
     * @dataProvider provideHeaderCommentFixerIsEnabledIfHeaderIsProvidedCases
     */
    public function testHeaderCommentFixerIsEnabledIfHeaderIsProvided(string $header): void
    {
        $rules = (new Config($header))->getRules();

        self::assertArrayHasKey('header_comment', $rules);
        $expected = [
            'comment_type' => 'PHPDoc',
            'header' => trim($header),
            'location' => 'after_declare_strict',
            'separate' => 'both',
        ];
        self::assertSame($expected, $rules['header_comment']);
    }

    /**
     * @psalm-return Generator<string, array{0: string}, mixed, void>
     * @return Generator
     */
    public static function provideHeaderCommentFixerIsEnabledIfHeaderIsProvidedCases(): Generator
    {
        $values = [
            'string-empty' => '',
            'string-not-empty' => 'foo',
            'string-with-line-feed-only' => "\n",
            'string-with-spaces-only' => ' ',
            'string-with-tab-only' => "\t",
        ];

        foreach ($values as $key => $value) {
            yield $key => [
                $value,
            ];
        }
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
     * @return ((string|string[])[]|true)[]
     *
     * @psalm-return array{combine_nested_dirname: true, list_syntax: array{syntax: string}, pow_to_exponentiation: true, random_api_migration: true, return_assignment: true, visibility_required: array{elements: array{0: string, 1: string, 2: string}}, void_return: true}
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
     * @return false[]
     *
     * @psalm-return array{heredoc_indentation: false}
     */
    protected function getPhp73Rules(): array
    {
        return ['heredoc_indentation' => false];
    }

    /**
     * @return ((bool|string|string[])[]|bool)[]
     *
     * @psalm-return array{@DoctrineAnnotation: true, align_multiline_comment: array{comment_type: string}, no_binary_string: true, no_unset_on_property: false, array_indentation: true, array_syntax: array{syntax: string}, logical_operators: true, pre_increment: false, backtick_to_shell_exec: true, blank_line_before_return: true, class_keyword_remove: false, combine_consecutive_issets: true, combine_consecutive_unsets: true, comment_to_phpdoc: false, compact_nullable_typehint: true, date_time_immutable: true, declare_strict_types: true, dir_constant: true, ereg_to_preg: true, escape_implicit_backslashes: true, explicit_indirect_variable: true, explicit_string_variable: true, ordered_class_elements: true, ordered_imports: array{importsOrder: array{0: string, 1: string, 2: string}}, final_class: true, final_internal_class: true, fully_qualified_strict_types: true, general_phpdoc_annotation_remove: false, hash_to_slash_comment: true, header_comment: false, linebreak_after_opening_tag: true, magic_constant_casing: true, mb_str_functions: false, method_argument_space: array{ensure_fully_multiline: true, keep_multiple_spaces_after_comma: false}, static_lambda: true, string_line_ending: true, method_chaining_indentation: true, modernize_types_casting: true, multiline_comment_opening_closing: true, multiline_whitespace_before_semicolons: array{strategy: string}, no_alternative_syntax: true, no_blank_lines_before_namespace: false, no_multiline_whitespace_before_semicolons: false, no_php4_constructor: false, no_short_echo_tag: true, no_useless_else: true, no_useless_return: true, no_superfluous_elseif: true, phpdoc_to_return_type: true, phpdoc_var_annotation_correct_order: true, no_superfluous_phpdoc_tags: true, not_operator_with_space: false, not_operator_with_successor_space: true, no_homoglyph_names: false, no_unset_cast: true, ordered_interfaces: true, phpdoc_add_missing_param_annotation: array{only_untyped: true}, phpdoc_order: true, phpdoc_types_order: array{null_adjustment: string, sort_algorithm: string}, protected_to_private: true, psr0: false, psr4: true, semicolon_after_instruction: true, simplified_null_return: false, strict_comparison: true, strict_param: true}
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
     * @return array<string, bool>
     *
     * @psalm-return array{@PSR2: true}
     */
    protected function getPsr2Rules(): array
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
    protected function getPsr12Rules(): array
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
     * @psalm-return array{set_type_to_cast: true, lowercase_static_reference: true, native_constant_invocation: true, blank_line_before_statement: array{statements: array{0: string, 1: string, 2: string, 3: string, 4: string, 5: string, 6: string, 7: string, 8: string, 9: string, 10: string, 11: string, 12: string, 13: string, 14: string, 15: string, 16: string, 17: string, 18: string, 19: string, 20: string}}, yoda_style: false, cast_spaces: true, class_attributes_separation: array{elements: array{0: string, 1: string}}, error_suppression: array{mute_deprecation_error: true, noise_remaining_usages: false}, standardize_increment: true, concat_space: array{spacing: string}, doctrine_annotation_array_assignment: array{operator: string}, doctrine_annotation_braces: array{syntax: string}, doctrine_annotation_indentation: true, doctrine_annotation_spaces: array{after_argument_assignments: false, after_array_assignments_colon: true, after_array_assignments_equals: false, around_parentheses: true, before_argument_assignments: false, before_array_assignments_colon: false, before_array_assignments_equals: false}, function_to_constant: true, function_typehint_space: true, fopen_flags: true, fopen_flag_order: true, heredoc_to_nowdoc: true, is_null: true, implode_call: true, include: true, increment_style: array{style: string}, no_unneeded_curly_braces: true, no_unneeded_final_method: true, non_printable_character: true, lowercase_cast: true, magic_method_casing: true, method_separation: false, native_function_casing: true, native_function_invocation: array{include: array{0: string}, scope: string, strict: true}, new_with_braces: true, native_function_type_declaration_casing: true, no_alias_functions: true, no_blank_lines_after_class_opening: true, no_blank_lines_after_phpdoc: true, no_empty_comment: true, no_empty_phpdoc: true, no_empty_statement: true, no_extra_consecutive_blank_lines: false, no_null_property_initialization: true, no_leading_import_slash: true, no_leading_namespace_whitespace: false, no_mixed_echo_print: array{use: string}, no_multiline_whitespace_around_double_arrow: true, no_short_bool_cast: true, no_singleline_whitespace_before_semicolons: true, no_spaces_around_offset: true, no_trailing_comma_in_list_call: true, no_trailing_comma_in_singleline_array: true, no_unneeded_control_parentheses: true, no_unreachable_default_argument_value: true, no_unused_imports: true, no_whitespace_before_comma_in_array: true, no_whitespace_in_blank_line: true, normalize_index_brace: true, object_operator_without_whitespace: true, phpdoc_align: true, phpdoc_annotation_without_dot: true, phpdoc_indent: true, phpdoc_inline_tag: true, phpdoc_no_access: true, phpdoc_no_alias_tag: true, phpdoc_no_empty_return: false, phpdoc_no_package: true, phpdoc_no_useless_inheritdoc: true, phpdoc_return_self_reference: true, phpdoc_scalar: true, phpdoc_separation: true, phpdoc_single_line_var_spacing: true, phpdoc_summary: true, phpdoc_to_comment: false, phpdoc_trim: true, phpdoc_types: true, phpdoc_var_without_name: true, phpdoc_trim_consecutive_blank_line_separation: true, return_type_declaration: true, self_accessor: false, short_scalar_cast: true, silenced_deprecation_error: false, simple_to_complex_string_variable: true, single_blank_line_before_namespace: true, single_quote: true, single_trait_insert_per_statement: true, single_line_comment_style: false, single_line_throw: false, standardize_not_equals: true, ternary_operator_spaces: true, ternary_to_null_coalescing: true, trailing_comma_in_multiline_array: true, trim_array_spaces: true, unary_operator_spaces: true, whitespace_after_comma_in_array: true}
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
            'native_function_invocation' => [
                'include' => ['@compiler_optimized'],
                'scope' => 'namespaced',
                'strict' => true,
            ],
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
            'single_line_throw' => false,
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
     * @return ((int|true)[]|bool)[]
     *
     * @psalm-return array{PedroTroller/comment_line_to_phpdoc_block: true, PedroTroller/exceptions_punctuation: true, PedroTroller/forbidden_functions: false, PedroTroller/ordered_with_getter_and_setter_first: true, PedroTroller/line_break_between_method_arguments: array{max-args: int, max-length: int, automatic-argument-merge: true}, PedroTroller/line_break_between_statements: true, PedroTroller/useless_code_after_return: true, PedroTroller/phpspec: false, PedroTroller/doctrine_migrations: true}
     */
    private function getPedroTrollerRules(): array
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
    private function getKubawerlosRules(): array
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
     * @return array-key[]
     *
     * @psalm-return list<array-key>
     */
    private function configuredFixers(): array
    {
        $config = new Config();

        /**
         * RuleSet::create() removes disabled fixers, to let's just enable them to make sure they not removed.
         *
         * @see https://github.com/FriendsOfPHP/PHP-CS-Fixer/pull/2361
         *
         * @psalm-suppress TooManyArguments
         */
        $rules = array_map(static function (): bool {
            return true;
        }, $config->getRules());

        /**
         * @psalm-suppress InternalClass
         * @psalm-suppress InternalMethod
         * @psalm-suppress MixedArgument
         * @psalm-suppress MixedMethodCall
         */
        return array_keys(RuleSet::create($rules)->getRules());
    }

    /**
     * @return string[]
     *
     * @psalm-return string[]
     */
    private function builtInFixers(): array
    {
        /** @var null|string[] $builtInFixers */
        static $builtInFixers;

        /**
         * @psalm-suppress InternalClass
         * @psalm-suppress InternalMethod
         */
        if ($builtInFixers === null) {
            $fixerFactory = FixerFactory::create();
            $fixerFactory->registerBuiltInFixers();

            $builtInFixers = array_map(static function (FixerInterface $fixer): string {
                return $fixer->getName();
            }, $fixerFactory->getFixers());
        }

        return $builtInFixers;
    }

    /**
     * @return string[]
     *
     * @psalm-return string[]
     */
    private function getDeprecatedFixer(): array
    {
        $phpCsFixerCustomFixers = [
            'PhpCsFixerCustomFixers/implode_call',
            'PhpCsFixerCustomFixers/no_two_consecutive_empty_lines',
            'PhpCsFixerCustomFixers/no_unneeded_concatenation',
            'PhpCsFixerCustomFixers/no_useless_class_comment',
            'PhpCsFixerCustomFixers/no_useless_constructor_comment',
            'PhpCsFixerCustomFixers/nullable_param_style',
            'PhpCsFixerCustomFixers/single_line_throw',
            'PhpCsFixerCustomFixers/phpdoc_var_annotation_correct_order',
        ];
        $pedroTrollerFixers = [
            'PedroTroller/single_line_comment',
            'PedroTroller/useless_comment',
            'PedroTroller/ordered_spec_elements',
            'PedroTroller/phpspec_scenario_return_type_declaration',
            'PedroTroller/phpspec_scenario_scope',
        ];

        return array_merge($phpCsFixerCustomFixers, $pedroTrollerFixers);
    }
}
