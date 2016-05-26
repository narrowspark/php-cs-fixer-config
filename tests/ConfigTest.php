<?php
namespace Narrowspark\CS\Config\Tests;

use Narrowspark\CS\Config\Config;
use PhpCsFixer\ConfigInterface;

class Refinery29Test extends \PHPUnit_Framework_TestCase
{
    public function testImplementsInterface()
    {
        $this->assertInstanceOf(ConfigInterface::class, (new Config()));
    }

    public function testValues()
    {
        $config = new Config();
        $this->assertSame('narrowspark', $config->getName());
        $this->assertSame('The configuration for Narrowspark PHP applications', $config->getDescription());
        $this->assertTrue($config->usingCache());
        $this->assertTrue($config->usingLinter());
        $this->assertFalse($config->getRiskyAllowed());
    }

    public function testHasPsr2Rules()
    {
        $this->assertHasRules(
            $this->getPsr2Rules(),
            (new Config())->getRules(),
            'PSR2'
        );
    }

    public function testHasSymfonyRules()
    {
        $this->assertHasRules(
            $this->getSymfonyRules(),
            (new Config())->getRules(),
            'Symfony'
        );
    }

    public function testHasContribRules()
    {
        $this->assertHasRules(
            $this->getContribRules(),
            (new Config())->getRules(),
            'Contrib'
        );
    }

    public function testDoesNotHaveHeaderCommentFixerByDefault()
    {
        $rules = (new Config())->getRules();

        $this->assertArrayHasKey('header_comment', $rules);
        $this->assertFalse($rules['header_comment']);
        $this->assertTrue($rules['no_blank_lines_before_namespace']);
    }

    public function testHasHeaderCommentFixerIfProvided()
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
    }

    /**
     * @dataProvider providerDoesNotHaveFixerEnabled
     *
     * @param string $fixer
     * @param string $reason
     */
    public function testDoesNotHaveRulesEnabled($fixer, $reason)
    {
        $config = new Config();
        $rule = [
            $fixer => false,
        ];

        $this->assertArraySubset($rule, $config->getRules(), true, sprintf(
            'Fixer "%s" should not be enabled, because "%s"',
            $fixer,
            $reason
        ));
    }

    /**
     * @return array
     */
    public function providerDoesNotHaveFixerEnabled()
    {
        $symfonyFixers = [
            'concat_without_spaces' => 'it conflicts with concat_with_spaces (which is enabled)',
            'self_accessor'         => 'it causes an edge case error',
            'phpdoc_summary'        => 'we have not decided to use this one (yet)',
        ];

        $contribFixers = [
            'align_double_arrow'                        => 'it conflicts with unalign_double_arrow (which is enabled)',
            'align_equals'                              => 'it conflicts with unalign_double (yet to be enabled)',
            'dir_constant'                              => 'it is a risky fixer',
            'echo_to_print'                             => 'we dont use it',
            'ereg_to_preg'                              => 'it changes behaviour',
            'header_comment'                            => 'it is not enabled by default',
            'long_array_syntax'                         => 'it conflicts with short_array_syntax (which is enabled)',
            'modernize_types_casting'                   => 'it is a risky fixer',
            'no_multiline_whitespaces_before_semicolon' => 'we have not decided to use this one (yet)',
            'no_php4_constructor'                       => 'it changes behaviour',
            'not_operators_with_space'                  => 'we do not need leading and trailing whitespace before !',
            'phpdoc_property'                           => 'we have not decided to use this one (yet)',
            'phpdoc_var_to_type'                        => 'it conflicts with phpdoc_type_to_var (which is enabled)',
            'php_unit_construct'                        => 'it changes behaviour',
            'php_unit_dedicate_assert'                  => 'it is a risky fixer',
            'php_unit_strict'                           => 'it changes behaviour',
            'print_to_echo'                             => 'we dont use it',
            'psr0'                                      => 'we are using PSR-4',
            'random_api_migration'                      => 'it is a risky fixer',
            'strict_comparison'                         => 'it changes behaviour',
            'strict_param'                              => 'it changes behaviour',
        ];

        $fixers = array_merge($contribFixers, $symfonyFixers);

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
    private function getPsr2Rules()
    {
        return [
            '@PSR2' => true,
        ];
    }

    /**
     * @return array
     */
    private function getSymfonyRules()
    {
        return [
            'blank_line_after_opening_tag'                => true,
            'blank_line_before_return'                    => true,
            'cast_spaces'                                 => true,
            'concat_without_spaces'                       => false,
            'function_typehint_space'                     => true,
            'hash_to_slash_comment'                       => true,
            'heredoc_to_nowdoc'                           => true,
            'include'                                     => true,
            'lowercase_cast'                              => true,
            'method_separation'                           => true,
            'native_function_casing'                      => true,
            'new_with_braces'                             => true,
            'no_alias_functions'                          => true,
            'no_blank_lines_after_class_opening'          => true,
            'no_blank_lines_after_phpdoc'                 => true,
            'no_blank_lines_between_uses'                 => true,
            'no_duplicate_semicolons'                     => true,
            'no_empty_statement'                          => true,
            'no_extra_consecutive_blank_lines'            => true,
            'no_leading_import_slash'                     => true,
            'no_leading_namespace_whitespace'             => true,
            'no_multiline_whitespace_around_double_arrow' => true,
            'no_short_bool_cast'                          => true,
            'no_singleline_whitespace_before_semicolons'  => true,
            'no_spaces_inside_offset'                     => true,
            'no_trailing_comma_in_list_call'              => true,
            'no_trailing_comma_in_singleline_array'       => true,
            'no_unneeded_control_parentheses'             => true,
            'no_unreachable_default_argument_value'       => true,
            'no_unused_imports'                           => true,
            'no_whitespace_before_comma_in_array'         => true,
            'no_whitespace_in_blank_lines'                => true,
            'object_operator_without_whitespace'          => true,
            'phpdoc_align'                                => true,
            'phpdoc_indent'                               => true,
            'phpdoc_inline_tag'                           => true,
            'phpdoc_no_access'                            => true,
            'phpdoc_no_empty_return'                      => true,
            'phpdoc_no_package'                           => true,
            'phpdoc_scalar'                               => true,
            'phpdoc_separation'                           => true,
            'phpdoc_single_line_var_spacing'              => true,
            'phpdoc_summary'                              => false,
            'phpdoc_to_comment'                           => true,
            'phpdoc_trim'                                 => true,
            'phpdoc_type_to_var'                          => true,
            'phpdoc_types'                                => true,
            'phpdoc_var_without_name'                     => true,
            'pre_increment'                               => true,
            'print_to_echo'                               => false,
            'self_accessor'                               => false,
            'short_scalar_cast'                           => true,
            'simplified_null_return'                      => true,
            'single_blank_line_before_namespace'          => true,
            'single_quote'                                => true,
            'space_after_semicolon'                       => true,
            'standardize_not_equals'                      => true,
            'ternary_operator_spaces'                     => true,
            'trailing_comma_in_multiline_array'           => true,
            'trim_array_spaces'                           => true,
            'unalign_double_arrow'                        => true,
            'unalign_equals'                              => true,
            'unary_operator_spaces'                       => true,
            'whitespace_after_comma_in_array'             => true,
        ];
    }

    /**
     * @return array
     */
    protected function getContribRules()
    {
        return [
            'align_double_arrow'                         => false,
            'align_equals'                               => false,
            'combine_consecutive_unsets'                 => true,
            'concat_with_spaces'                         => true,
            'ereg_to_preg'                               => false,
            'echo_to_print'                              => false,
            'dir_constant'                               => false,
            'header_comment'                             => false,
            'linebreak_after_opening_tag'                => true,
            'long_array_syntax'                          => false,
            'modernize_types_casting'                    => false,
            'no_multiline_whitespaces_before_semicolon'  => false,
            'no_php4_constructor'                        => false,
            'no_short_echo_tag'                          => true,
            'no_useless_return'                          => true,
            'not_operators_with_space'                   => false,
            'not_operator_with_successor_space'          => true,
            'ordered_class_elements'                     => true,
            'ordered_imports'                            => true,
            'phpdoc_order'                               => true,
            'phpdoc_property'                            => false,
            'phpdoc_var_to_type'                         => false,
            'php_unit_construct'                         => false,
            'php_unit_dedicate_assert'                   => false,
            'php_unit_strict'                            => false,
            'psr0'                                       => false,
            'random_api_migration'                       => false,
            'short_array_syntax'                         => true,
            'strict_comparison'                          => false,
            'strict_param'                               => false,
        ];
    }

    /**
     * @param array  $expected
     * @param array  $actual
     * @param string $set
     */
    private function assertHasRules(array $expected, array $actual, $set)
    {
        foreach ($expected as $fixer => $isEnabled) {
            $this->assertArrayHasKey($fixer, $actual, sprintf(
                'Failed to assert that a rule for fixer "%s" (in set "%s") exists.,',
                $fixer,
                $set
            ));
            $this->assertSame($isEnabled, $actual[$fixer], sprintf(
                'Failed to assert that fixer "%s" (in set "%s") is %s.',
                $fixer,
                $set,
                $isEnabled === true ? 'enabled' : 'disabled'
            ));
        }
    }
}
