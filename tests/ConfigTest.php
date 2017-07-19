<?php
declare(strict_types=1);
namespace Narrowspark\CS\Config\Tests;

use Narrowspark\CS\Config\Config;
use PhpCsFixer\ConfigInterface;
use PhpCsFixer\FixerFactory;
use PhpCsFixer\RuleSet;
use PHPUnit\Framework\TestCase;
use ReflectionProperty;

class ConfigTest extends TestCase
{
    public function testImplementsInterface(): void
    {
        $this->assertInstanceOf(ConfigInterface::class, (new Config()));
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
            $this->getPhp71Rules()
        );
        $rules = (new Config())->getRules();

        foreach ($rules as $key => $value) {
            $this->assertTrue(isset($testRules[$key]), '[' . $key . '] Rule is missing.');
        }

        $this->assertSame(\count($rules), (\count($testRules)));
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
        $config = new Config();
        $config->setHeader($header);
        $rules = $config->getRules();

        $this->assertArrayHasKey('header_comment', $rules);

        $expected = [
            'header' => $header,
        ];
        $this->assertSame($expected, $rules['header_comment']);
        $this->assertFalse($rules['no_blank_lines_before_namespace']);
        $this->assertTrue($rules['single_blank_line_before_namespace']);
    }

    public function testAllConfiguredRulesAreBuiltIn(): void
    {
        $fixersNotBuiltIn = \array_diff(
            $this->configuredFixers(),
            $this->builtInFixers()
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
            'ereg_to_preg'                              => 'it changes behaviour',
            'header_comment'                            => 'it is not enabled by default',
            'array_syntax'                              => [
                'long'                                  => 'it conflicts with short array syntax (which is enabled)',
            ],
            'no_php4_constructor'                       => 'it changes behaviour',
            'not_operator_with_space'                   => 'we do not need leading and trailing whitespace before !',
            'php_unit_strict'                           => 'it changes behaviour',
            'psr0'                                      => 'we are using PSR-4',
            'strict_comparison'                         => 'it changes behaviour',
            'simplified_null_return'                    => 'it changes behaviour on void return',
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
            '@PHP71Migration:risky' => true,
            'list_syntax'           => [
                'syntax' => 'short',
            ],
        ];
    }

    /**
     * @return array
     */
    protected function getContribRules(): array
    {
        return [
            'array_syntax'                              => [
                'syntax' => 'short',
            ],
            'class_keyword_remove'                      => false,
            'combine_consecutive_unsets'                => true,
            'declare_strict_types'                      => true,
            'dir_constant'                              => true,
            'ereg_to_preg'                              => false,
            'general_phpdoc_annotation_remove'          => false,
            'header_comment'                            => false,
            'linebreak_after_opening_tag'               => true,
            'magic_constant_casing'                     => true,
            'mb_str_functions'                          => true,
            'method_argument_space'                     => [
                'ensure_fully_multiline'           => true,
                'keep_multiple_spaces_after_comma' => false,
            ],
            'modernize_types_casting'                   => true,
            'no_blank_lines_before_namespace'           => true,
            'no_multiline_whitespace_before_semicolons' => true,
            'no_php4_constructor'                       => false,
            'no_short_echo_tag'                         => true,
            'no_useless_else'                           => true,
            'no_useless_return'                         => true,
            'not_operator_with_space'                   => false,
            'not_operator_with_successor_space'         => true,
            'ordered_class_elements'                    => true,
            'ordered_imports'                           => true,
            'php_unit_strict'                           => false,
            'php_unit_test_class_requires_covers'       => false,
            'phpdoc_add_missing_param_annotation'       => [
                'only_untyped'                          => false,
            ],
            'phpdoc_order'                              => true,
            'phpdoc_types_order'                        => true,
            'protected_to_private'                      => true,
            'psr0'                                      => false,
            'psr4'                                      => true,
            'semicolon_after_instruction'               => true,
            'simplified_null_return'                    => false,
            'strict_comparison'                         => false,
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
    private function getSymfonyRules(): array
    {
        return [
            'binary_operator_spaces'                      => [
                'align_double_arrow'                      => true,
                'align_equals'                            => true,
            ],
            'blank_line_after_opening_tag'                => false,
            'blank_line_before_statement'                 => true,
            'cast_spaces'                                 => true,
            'concat_space'                                => [
                'spacing'                                 => 'one',
            ],
            'declare_equal_normalize'                     => true,
            'doctrine_annotation_array_assignment'        => true,
            'doctrine_annotation_braces'                  => true,
            'doctrine_annotation_indentation'             => true,
            'doctrine_annotation_spaces'                  => true,
            'function_to_constant'                        => true,
            'function_typehint_space'                     => true,
            'heredoc_to_nowdoc'                           => true,
            'is_null'                                     => true,
            'include'                                     => true,
            'lowercase_cast'                              => true,
            'method_separation'                           => true,
            'native_function_casing'                      => true,
            'native_function_invocation'                  => false,
            'new_with_braces'                             => true,
            'no_alias_functions'                          => false,
            'no_blank_lines_after_class_opening'          => true,
            'no_blank_lines_after_phpdoc'                 => true,
            'no_empty_comment'                            => true,
            'no_empty_phpdoc'                             => true,
            'no_empty_statement'                          => true,
            'no_extra_consecutive_blank_lines'            => [
                'tokens' => [
                    'break',
                    'continue',
                    'curly_brace_block',
                    'extra',
                    'parenthesis_brace_block',
                    'return',
                    'square_brace_block',
                    'throw',
                    'use',
                    'use_trait',
                ],
            ],
            'no_null_property_initialization'             => true,
            'no_leading_import_slash'                     => true,
            'no_leading_namespace_whitespace'             => true,
            'no_mixed_echo_print'                         => [
                'use'                                     => 'echo',
            ],
            'no_multiline_whitespace_around_double_arrow' => true,
            'no_short_bool_cast'                          => true,
            'no_singleline_whitespace_before_semicolons'  => true,
            'no_spaces_around_offset'                     => true,
            'no_trailing_comma_in_list_call'              => true,
            'no_trailing_comma_in_singleline_array'       => true,
            'no_unneeded_control_parentheses'             => true,
            'no_unreachable_default_argument_value'       => true,
            'no_unused_imports'                           => true,
            'no_whitespace_before_comma_in_array'         => true,
            'no_whitespace_in_blank_line'                 => true,
            'normalize_index_brace'                       => true,
            'object_operator_without_whitespace'          => true,
            'php_unit_construct'                          => true,
            'php_unit_dedicate_assert'                    => true,
            'php_unit_fqcn_annotation'                    => true,
            'phpdoc_align'                                => true,
            'phpdoc_annotation_without_dot'               => true,
            'phpdoc_indent'                               => true,
            'phpdoc_inline_tag'                           => true,
            'phpdoc_no_access'                            => true,
            'phpdoc_no_alias_tag'                         => true,
            'phpdoc_no_empty_return'                      => false,
            'phpdoc_no_package'                           => true,
            'phpdoc_no_useless_inheritdoc'                => true,
            'phpdoc_return_self_reference'                => true,
            'phpdoc_scalar'                               => true,
            'phpdoc_separation'                           => true,
            'phpdoc_single_line_var_spacing'              => true,
            'phpdoc_summary'                              => true,
            'phpdoc_to_comment'                           => true,
            'phpdoc_trim'                                 => true,
            'phpdoc_types'                                => true,
            'phpdoc_var_without_name'                     => true,
            'pre_increment'                               => true,
            'return_type_declaration'                     => true,
            'self_accessor'                               => false,
            'short_scalar_cast'                           => true,
            'silenced_deprecation_error'                  => false,
            'single_blank_line_before_namespace'          => false,
            'single_quote'                                => true,
            'space_after_semicolon'                       => true,
            'single_line_comment_style'                   => true,
            'standardize_not_equals'                      => true,
            'ternary_operator_spaces'                     => true,
            'ternary_to_null_coalescing'                  => true,
            'trailing_comma_in_multiline_array'           => true,
            'trim_array_spaces'                           => true,
            'unary_operator_spaces'                       => true,
            'whitespace_after_comma_in_array'             => true,
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
    private function configuredFixers()
    {
        $config = new Config();

        /**
         * RuleSet::create() removes disabled fixers, to let's just enable them to make sure they not removed.
         *
         * @see https://github.com/FriendsOfPHP/PHP-CS-Fixer/pull/2361
         */
        $rules = \array_map(function () {
            return true;
        }, $config->getRules());

        return \array_keys(RuleSet::create($rules)->getRules());
    }

    /**
     * @return string[]
     */
    private function builtInFixers()
    {
        $fixerFactory = FixerFactory::create();
        $fixerFactory->registerBuiltInFixers();

        $reflection = new ReflectionProperty(FixerFactory::class, 'fixersByName');
        $reflection->setAccessible(true);

        return \array_keys($reflection->getValue($fixerFactory));
    }
}
