<?php
namespace Narrowspark\CS\Config;

use PhpCsFixer\Config as CsConfig;

class Config extends CsConfig
{
    /**
     * @var string
     */
    private $header;

    public function __construct(
        $name = 'narrowspark',
        $description = 'The configuration for Narrowspark PHP applications'
    ) {
        parent::__construct($name, $description);
    }

    /**
     * @return bool
     */
    public function usingCache()
    {
        return true;
    }

    /**
     * @return bool
     */
    public function usingLinter()
    {
        return true;
    }

    /**
     * @return bool
     */
    public function getRiskyAllowed()
    {
        return false;
    }

    /**
     * @return array
     */
    public function getRules()
    {
        return array_merge(
            $this->getPsr2Rules(),
            $this->getSymfonyRules(),
            $this->getContribRules()
        );
    }

    public function setHeader($header)
    {
        $this->header = $header;
    }

    /**
     * @return array
     */
    protected function getContribRules()
    {
        $rules = [
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

        if ($this->header !== null) {
            $rules['header_comment'] = [
                'header' => $this->header,
            ];
            $rules['no_blank_lines_before_namespace'] = false;
        } else {
            $rules['no_blank_lines_before_namespace'] = true;
        }

        return $rules;
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
        $rules = [
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

        if ($this->header !== null) {
            $rules['single_blank_line_before_namespace'] = true;
        } else {
            $rules['single_blank_line_before_namespace'] = false;
        }

        return $rules;
    }
}
