<?php
declare(strict_types=1);
namespace Narrowspark\CS\Config\Tests;

use Narrowspark\CS\Config\Config;
use PhpCsFixer\ConfigInterface;
use PhpCsFixer\FixerFactory;
use PhpCsFixer\RuleSet;
use PhpCsFixerCustomFixers\Fixer\InternalClassCasingFixer;
use PhpCsFixerCustomFixers\Fixer\MultilineCommentOpeningClosingAloneFixer;
use PhpCsFixerCustomFixers\Fixer\NoCommentedOutCodeFixer;
use PhpCsFixerCustomFixers\Fixer\NoDoctrineMigrationsGeneratedCommentFixer;
use PhpCsFixerCustomFixers\Fixer\NoImportFromGlobalNamespaceFixer;
use PhpCsFixerCustomFixers\Fixer\NoLeadingSlashInGlobalNamespaceFixer;
use PhpCsFixerCustomFixers\Fixer\NoNullableBooleanTypeFixer;
use PhpCsFixerCustomFixers\Fixer\NoPhpStormGeneratedCommentFixer;
use PhpCsFixerCustomFixers\Fixer\NoReferenceInFunctionDefinitionFixer;
use PhpCsFixerCustomFixers\Fixer\NoUnneededConcatenationFixer;
use PhpCsFixerCustomFixers\Fixer\NoUselessCommentFixer;
use PhpCsFixerCustomFixers\Fixer\NoUselessDoctrineRepositoryCommentFixer;
use PhpCsFixerCustomFixers\Fixer\NullableParamStyleFixer;
use PhpCsFixerCustomFixers\Fixer\OperatorLinebreakFixer;
use PhpCsFixerCustomFixers\Fixer\PhpdocNoIncorrectVarAnnotationFixer;
use PhpCsFixerCustomFixers\Fixer\PhpdocNoSuperfluousParamFixer;
use PhpCsFixerCustomFixers\Fixer\PhpdocParamOrderFixer;
use PhpCsFixerCustomFixers\Fixer\PhpdocParamTypeFixer;
use PhpCsFixerCustomFixers\Fixer\PhpdocSelfAccessorFixer;
use PhpCsFixerCustomFixers\Fixer\PhpdocSingleLineVarFixer;
use PhpCsFixerCustomFixers\Fixer\SingleSpaceAfterStatementFixer;
use PhpCsFixerCustomFixers\Fixer\SingleSpaceBeforeStatementFixer;
use PHPUnit\Framework\TestCase;
use ReflectionProperty;

/**
 * @internal
 */
final class ConfigTest extends TestCase
{
    public function testImplementsInterface(): void
    {
        $this->assertInstanceOf(ConfigInterface::class, new Config());
    }

    public function testValues(): void
    {
        $config = new Config();

        $this->assertSame('narrowspark', $config->getName());
    }

    public function testHasPsr2Rules(): void
    {
        $this->assertHasRules(
            $this->getPsr2Rules(),
            (new Config())->getRules(),
            'PSR2'
        );
    }

    public function testHasSymfonyRules(): void
    {
        $this->assertHasRules(
            $this->getSymfonyRules(),
            (new Config())->getRules(),
            'Symfony'
        );
    }

    public function testHasContribRules(): void
    {
        $this->assertHasRules(
            $this->getContribRules(),
            (new Config())->getRules(),
            'Contrib'
        );
    }

    public function testIfAllRulesAreTested(): void
    {
        $testRules = \array_merge(
            $this->getPsr2Rules(),
            $this->getContribRules(),
            $this->getSymfonyRules(),
            $this->getPhp71Rules(),
            $this->getPhp73Rules(),
            $this->getPHPUnitRules(),
            $this->getPedroTrollerRules(),
            $this->getKubawerlosRules()
        );
        $rules = (new Config())->getRules();

        foreach ($rules as $key => $value) {
            $this->assertTrue(isset($testRules[$key]), '[' . $key . '] Rule is missing.');
        }

        $this->assertCount(\count($testRules), $rules);
    }

    public function testDoesNotHaveHeaderCommentFixerByDefault(): void
    {
        $rules = (new Config())->getRules();

        $this->assertArrayHasKey('header_comment', $rules);
        $this->assertFalse($rules['header_comment']);
        $this->assertTrue($rules['no_blank_lines_before_namespace']);
        $this->assertFalse($rules['single_blank_line_before_namespace']);
    }

    public function testHasHeaderCommentFixerIfProvided(): void
    {
        $header = 'foo';
        $config = new Config($header);
        $rules  = $config->getRules();

        $this->assertArrayHasKey('header_comment', $rules);

        $expected = [
            'comment_type' => 'PHPDoc',
            'header'       => $header,
            'location'     => 'after_declare_strict',
            'separate'     => 'both',
        ];
        $this->assertSame($expected, $rules['header_comment']);
        $this->assertTrue($rules['no_blank_lines_before_namespace']);
        $this->assertFalse($rules['single_blank_line_before_namespace']);
    }

    public function testAllConfiguredRulesAreBuiltIn(): void
    {
        $pedroTrollerRules = [];

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

        $fixersNotBuiltIn = \array_diff(
            $this->configuredFixers(),
            \array_merge($this->builtInFixers(), $pedroTrollerRules, $kubawerlosRules)
        );

        $this->assertEmpty($fixersNotBuiltIn, \sprintf(
            'Failed to assert that fixers for the rules "%s" are built in',
            \implode('", "', $fixersNotBuiltIn)
        ));
    }

    /**
     * @dataProvider providerDoesNotHaveFixerEnabled
     *
     * @param string       $fixer
     * @param array|string $reason
     */
    public function testDoesNotHaveRulesEnabled(string $fixer, $reason): void
    {
        $config = new Config();
        $rule   = [
            $fixer => false,
        ];

        if ($fixer === 'array_syntax') {
            $this->assertNotSame(['syntax' => 'long'], $config->getRules()['array_syntax'], \sprintf(
                'Fixer "%s" should not be enabled, because "%s"',
                $fixer,
                $reason['long']
            ));
        } else {
            $this->assertArraySubset($rule, $config->getRules(), true, \sprintf(
                'Fixer "%s" should not be enabled, because "%s"',
                $fixer,
                $reason
            ));
        }
    }

    /**
     * @return array
     */
    public function providerDoesNotHaveFixerEnabled(): array
    {
        $symfonyFixers = [
            'self_accessor' => 'it causes an edge case error',
        ];

        $contribFixers = [
            'header_comment'          => 'it is not enabled by default',
            'array_syntax'            => [
                'long' => 'it conflicts with short array syntax (which is enabled)',
            ],
            'no_php4_constructor'     => 'it changes behaviour',
            'not_operator_with_space' => 'we do not need leading and trailing whitespace before !',
            'php_unit_strict'         => 'it changes behaviour',
            'psr0'                    => 'we are using PSR-4',
            'no_homoglyph_names'      => 'renames classes and cannot rename the files. You might have string references to renamed code (``$$name``)',
            'simplified_null_return'  => 'it changes behaviour on void return',

            'fopen_flags'             => 'it changes r+b to b+r and w+b and b+w',
            'fopen_flag_order'        => 'it changes r+b to b+r and w+b and b+w',
        ];

        $fixers = \array_merge($contribFixers, $symfonyFixers);

        $data = [];

        foreach ($fixers as $fixer => $reason) {
            $data[] = [
                $fixer,
                $reason,
            ];
        }

        return $data;
    }

    /**
     * @return array
     */
    public function getPhp71Rules(): array
    {
        return [
            'combine_nested_dirname' => true,
            'list_syntax'            => [
                'syntax' => 'short',
            ],
            'pow_to_exponentiation' => true,
            'random_api_migration'  => true,
            'return_assignment'     => true,
            'visibility_required'   => [
                'elements' => [
                    'const',
                    'method',
                    'property',
                ],
            ],
            'void_return' => true,
        ];
    }

    public function getPhp73Rules(): array
    {
        return [
            'heredoc_indentation' => true,
        ];
    }

    /**
     * @return array
     */
    public function getPedroTrollerRules(): array
    {
        return [
            'PedroTroller/comment_line_to_phpdoc_block'         => false,
            'PedroTroller/exceptions_punctuation'               => true,
            'PedroTroller/forbidden_functions'                  => false,
            'PedroTroller/ordered_with_getter_and_setter_first' => true,
            'PedroTroller/line_break_between_method_arguments'  => [
                'max-args'                 => 4,
                'max-length'               => 120,
                'automatic-argument-merge' => true,
            ],
            'PedroTroller/line_break_between_statements' => true,
            'PedroTroller/useless_code_after_return'     => false,
            'PedroTroller/phpspec'                       => false,
        ];
    }

    /**
     * @return array
     */
    public function getKubawerlosRules(): array
    {
        return [
            InternalClassCasingFixer::name()                  => true,
            MultilineCommentOpeningClosingAloneFixer::name()  => false,
            NoCommentedOutCodeFixer::name()                   => true,
            NoDoctrineMigrationsGeneratedCommentFixer::name() => true,
            NoImportFromGlobalNamespaceFixer::name()          => false,
            NoLeadingSlashInGlobalNamespaceFixer::name()      => true,
            NoNullableBooleanTypeFixer::name()                => false,
            NoPhpStormGeneratedCommentFixer::name()           => true,
            NoReferenceInFunctionDefinitionFixer::name()      => false,
            NoUnneededConcatenationFixer::name()              => true,
            NoUselessCommentFixer::name()                     => false,
            NoUselessDoctrineRepositoryCommentFixer::name()   => true,
            NullableParamStyleFixer::name()                   => false,
            OperatorLinebreakFixer::name()                    => true,
            PhpdocNoIncorrectVarAnnotationFixer::name()       => true,
            PhpdocNoSuperfluousParamFixer::name()             => true,
            PhpdocParamOrderFixer::name()                     => true,
            PhpdocParamTypeFixer::name()                      => true,
            PhpdocSelfAccessorFixer::name()                   => true,
            PhpdocSingleLineVarFixer::name()                  => true,
            SingleSpaceAfterStatementFixer::name()            => true,
            SingleSpaceBeforeStatementFixer::name()           => true,
        ];
    }

    /**
     * @return array
     */
    protected function getContribRules(): array
    {
        return [
            '@DoctrineAnnotation'                       => true,
            'align_multiline_comment'                   => [
                'comment_type' => 'all_multiline',
            ],
            'no_binary_string'                          => true,
            'no_unset_on_property'                      => false,
            'array_indentation'                         => true,
            'array_syntax'                              => [
                'syntax' => 'short',
            ],
            'logical_operators'                         => true,
            'pre_increment'                             => false,
            'backtick_to_shell_exec'                    => true,
            'blank_line_before_return'                  => true,
            'class_keyword_remove'                      => false,
            'combine_consecutive_issets'                => true,
            'combine_consecutive_unsets'                => true,
            'comment_to_phpdoc'                         => false,
            'compact_nullable_typehint'                 => true,
            'date_time_immutable'                       => false,
            'declare_strict_types'                      => true,
            'dir_constant'                              => true,
            'ereg_to_preg'                              => true,
            'escape_implicit_backslashes'               => true,
            'explicit_indirect_variable'                => true,
            'explicit_string_variable'                  => true,
            'final_class'                               => true,
            'final_internal_class'                      => true,
            'fully_qualified_strict_types'              => true,
            'general_phpdoc_annotation_remove'          => false,
            'hash_to_slash_comment'                     => true,
            'header_comment'                            => false,
            'linebreak_after_opening_tag'               => true,
            'magic_constant_casing'                     => true,
            'mb_str_functions'                          => false,
            'method_argument_space'                     => [
                'ensure_fully_multiline'           => true,
                'keep_multiple_spaces_after_comma' => false,
            ],
            'static_lambda'                             => true,
            'string_line_ending'                        => true,
            'method_chaining_indentation'               => true,
            'modernize_types_casting'                   => true,
            'multiline_comment_opening_closing'         => true,
            'multiline_whitespace_before_semicolons'    => [
                'strategy' => 'no_multi_line',
            ],
            'no_alternative_syntax'                     => true,
            'no_blank_lines_before_namespace'           => true,
            'no_multiline_whitespace_before_semicolons' => false,
            'no_php4_constructor'                       => false,
            'no_short_echo_tag'                         => true,
            'no_useless_else'                           => true,
            'no_useless_return'                         => true,
            'no_superfluous_elseif'                     => true,
            'phpdoc_to_return_type'                     => true,
            'phpdoc_var_annotation_correct_order'       => true,
            'no_superfluous_phpdoc_tags'                => false,
            'not_operator_with_space'                   => false,
            'not_operator_with_successor_space'         => true,
            'no_homoglyph_names'                        => false,
            'no_unset_cast'                             => true,
            'ordered_class_elements'                    => true,
            'ordered_imports'                           => true,
            'ordered_interfaces'                        => true,
            'php_unit_strict'                           => false,
            'php_unit_method_casing'                    => true,
            'php_unit_test_class_requires_covers'       => false,
            'phpdoc_add_missing_param_annotation'       => [
                'only_untyped'                          => false,
            ],
            'phpdoc_order'                              => true,
            'phpdoc_types_order'                        => [
                'null_adjustment' => 'always_first',
                'sort_algorithm'  => 'alpha',
            ],
            'protected_to_private'                      => true,
            'psr0'                                      => false,
            'psr4'                                      => true,
            'semicolon_after_instruction'               => true,
            'simplified_null_return'                    => false,
            'strict_comparison'                         => true,
            'strict_param'                              => true,
        ];
    }

    /**
     * @return array
     */
    private function getPsr2Rules(): array
    {
        return [
            '@PSR2' => true,
        ];
    }

    /**
     * @return array
     */
    private function getPHPUnitRules(): array
    {
        return [
            'php_unit_expectation' => [
                'target' => 'newest',
            ],
            'php_unit_dedicate_assert_internal_type' => true,
            'php_unit_mock'                          => true,
            'php_unit_mock_short_will_return'        => true,
            'php_unit_namespaced'                    => [
                'target' => 'newest',
            ],
            'php_unit_no_expectation_annotation' => [
                'target'          => 'newest',
                'use_class_const' => true,
            ],
            'php_unit_test_case_static_method_calls' => [
                'call_type' => 'this',
            ],
            'php_unit_internal_class' => [
                'types' => [
                    'abstract',
                    'final',
                    'normal',
                ],
            ],
            'php_unit_ordered_covers'              => true,
            'php_unit_set_up_tear_down_visibility' => true,
            'php_unit_strict'                      => false,
            'php_unit_size_class'                  => true,
            'php_unit_test_annotation'             => true,
            'php_unit_test_class_requires_covers'  => false,
        ];
    }

    /**
     * @return array
     */
    private function getSymfonyRules(): array
    {
        return [
            'binary_operator_spaces'                      => [
                'default' => 'align',
            ],
            'set_type_to_cast'                            => true,
            'lowercase_static_reference'                  => true,
            'native_constant_invocation'                  => true,
            'blank_line_after_opening_tag'                => false,
            'blank_line_before_statement'                 => [
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
            'yoda_style'                                  => false,
            'cast_spaces'                                 => true,
            'class_attributes_separation'                 => [
                'elements' => [
                    'method',
                    'property',
                ],
            ],
            'error_suppression' => [
                'mute_deprecation_error' => true,
                'noise_remaining_usages' => false,
            ],
            'standardize_increment'                       => true,
            'concat_space'                                => [
                'spacing'                                 => 'one',
            ],
            'declare_equal_normalize'                     => true,
            'doctrine_annotation_array_assignment'        => [
                'operator' => ':',
            ],
            'doctrine_annotation_braces'                  => [
                'syntax' => 'without_braces',
            ],
            'doctrine_annotation_indentation'             => true,
            'doctrine_annotation_spaces'                  => [
                'after_argument_assignments'      => false,
                'after_array_assignments_colon'   => true,
                'after_array_assignments_equals'  => false,
                'around_parentheses'              => true,
                'before_argument_assignments'     => false,
                'before_array_assignments_colon'  => false,
                'before_array_assignments_equals' => false,
            ],
            'function_to_constant'                        => true,
            'function_typehint_space'                     => true,
            'fopen_flags'                                 => false,
            'fopen_flag_order'                            => false,
            'heredoc_to_nowdoc'                           => true,
            'is_null'                                     => true,
            'implode_call'                                => true,
            'include'                                     => true,
            'increment_style'                             => [
                'style' => 'post',
            ],
            'no_unneeded_curly_braces'                    => true,
            'no_unneeded_final_method'                    => true,
            'non_printable_character'                     => true,
            'lowercase_cast'                              => true,
            'magic_method_casing'                         => true,
            'method_separation'                           => true,
            'native_function_casing'                      => true,
            'native_function_invocation'                  => true,
            'new_with_braces'                             => true,
            'native_function_type_declaration_casing'     => true,
            'no_alias_functions'                          => true,
            'no_blank_lines_after_class_opening'          => true,
            'no_blank_lines_after_phpdoc'                 => true,
            'no_empty_comment'                            => true,
            'no_empty_phpdoc'                             => true,
            'no_empty_statement'                          => true,
            'no_extra_blank_lines'                        => [
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
            'no_extra_consecutive_blank_lines'            => false,
            'no_null_property_initialization'             => true,
            'no_leading_import_slash'                     => true,
            'no_leading_namespace_whitespace'             => true,
            'no_mixed_echo_print'                         => [
                'use'                                     => 'echo',
            ],
            'no_multiline_whitespace_around_double_arrow'   => true,
            'no_short_bool_cast'                            => true,
            'no_singleline_whitespace_before_semicolons'    => true,
            'no_spaces_around_offset'                       => true,
            'no_trailing_comma_in_list_call'                => true,
            'no_trailing_comma_in_singleline_array'         => true,
            'no_unneeded_control_parentheses'               => true,
            'no_unreachable_default_argument_value'         => true,
            'no_unused_imports'                             => true,
            'no_whitespace_before_comma_in_array'           => true,
            'no_whitespace_in_blank_line'                   => true,
            'normalize_index_brace'                         => true,
            'object_operator_without_whitespace'            => true,
            'php_unit_construct'                            => true,
            'php_unit_dedicate_assert'                      => true,
            'php_unit_fqcn_annotation'                      => true,
            'phpdoc_align'                                  => true,
            'phpdoc_annotation_without_dot'                 => true,
            'phpdoc_indent'                                 => true,
            'phpdoc_inline_tag'                             => true,
            'phpdoc_no_access'                              => true,
            'phpdoc_no_alias_tag'                           => true,
            'phpdoc_no_empty_return'                        => false,
            'phpdoc_no_package'                             => true,
            'phpdoc_no_useless_inheritdoc'                  => true,
            'phpdoc_return_self_reference'                  => true,
            'phpdoc_scalar'                                 => true,
            'phpdoc_separation'                             => true,
            'phpdoc_single_line_var_spacing'                => true,
            'phpdoc_summary'                                => true,
            'phpdoc_to_comment'                             => false,
            'phpdoc_trim'                                   => true,
            'phpdoc_types'                                  => true,
            'phpdoc_var_without_name'                       => true,
            'phpdoc_trim_consecutive_blank_line_separation' => true,
            'return_type_declaration'                       => true,
            'self_accessor'                                 => false,
            'short_scalar_cast'                             => true,
            'silenced_deprecation_error'                    => false,
            'simple_to_complex_string_variable'             => true,
            'single_blank_line_before_namespace'            => false,
            'single_quote'                                  => true,
            'single_trait_insert_per_statement'             => true,
            'space_after_semicolon'                         => true,
            'single_line_comment_style'                     => false,
            'standardize_not_equals'                        => true,
            'ternary_operator_spaces'                       => true,
            'ternary_to_null_coalescing'                    => true,
            'trailing_comma_in_multiline_array'             => true,
            'trim_array_spaces'                             => true,
            'unary_operator_spaces'                         => true,
            'whitespace_after_comma_in_array'               => true,
        ];
    }

    /**
     * @param array  $expected
     * @param array  $actual
     * @param string $set
     */
    private function assertHasRules(array $expected, array $actual, string $set): void
    {
        foreach ($expected as $fixer => $isEnabled) {
            $this->assertArrayHasKey($fixer, $actual, \sprintf(
                'Failed to assert that a rule for fixer "%s" (in set "%s") exists.,',
                $fixer,
                $set
            ));
            $this->assertSame($isEnabled, $actual[$fixer], \sprintf(
                'Failed to assert that fixer "%s" (in set "%s") is %s.',
                $fixer,
                $set,
                $isEnabled === true ? 'enabled' : 'disabled'
            ));
        }
    }

    /**
     * @return string[]
     */
    private function configuredFixers(): array
    {
        $config = new Config();

        /**
         * RuleSet::create() removes disabled fixers, to let's just enable them to make sure they not removed.
         *
         * @see https://github.com/FriendsOfPHP/PHP-CS-Fixer/pull/2361
         */
        $rules = \array_map(static function () {
            return true;
        }, $config->getRules());

        return \array_keys(RuleSet::create($rules)->getRules());
    }

    /**
     * @throws \ReflectionException
     *
     * @return string[]
     */
    private function builtInFixers(): array
    {
        $fixerFactory = FixerFactory::create();
        $fixerFactory->registerBuiltInFixers();

        $reflection = new ReflectionProperty(FixerFactory::class, 'fixersByName');
        $reflection->setAccessible(true);

        return \array_keys($reflection->getValue($fixerFactory));
    }
}
