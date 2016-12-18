<?php
declare(strict_types=1);
namespace Narrowspark\CS\Config;

use PhpCsFixer\Config as CsConfig;

class Config extends CsConfig
{
    /**
     * @var string|null
     */
    private $header;

    /**
     * Create new Config instance.
     *
     * @param string $name
     */
    public function __construct(string $name = 'narrowspark')
    {
        parent::__construct($name);

        $this->setRiskyAllowed(true);
    }

    /**
     * @return array
     */
    public function getRules(): array
    {
        $rules = [
            '@PSR2'        => true,
            'array_syntax' => [
                'syntax' => 'short',
            ],
            'binary_operator_spaces' => [
                'align_double_arrow' => true,
                'align_equals'       => true,
            ],
            'blank_line_after_opening_tag' => false,
            'blank_line_before_return'     => true,
            'cast_spaces'                  => true,
            'class_keyword_remove'         => false,
            'combine_consecutive_unsets'   => true,
            'concat_space'                 => [
                'spacing' => 'one',
            ],
            'declare_equal_normalize'            => true,
            'declare_strict_types'               => true,
            'dir_constant'                       => true,
            'ereg_to_preg'                       => false,
            'function_typehint_space'            => true,
            'general_phpdoc_annotation_remove'   => false,
            'hash_to_slash_comment'              => true,
            'header_comment'                     => false,
            'heredoc_to_nowdoc'                  => true,
            'include'                            => true,
            'linebreak_after_opening_tag'        => true,
            'lowercase_cast'                     => true,
            'mb_str_functions'                   => true,
            'method_separation'                  => true,
            'modernize_types_casting'            => true,
            'native_function_casing'             => true,
            'new_with_braces'                    => true,
            'no_alias_functions'                 => true,
            'no_blank_lines_after_class_opening' => true,
            'no_blank_lines_after_phpdoc'        => true,
            'no_blank_lines_before_namespace'    => true,
            'no_empty_comment'                   => true,
            'no_empty_phpdoc'                    => true,
            'no_empty_statement'                 => true,
            'no_extra_consecutive_blank_lines'   => [
                'break',
                'continue',
                'curly_brace_block',
                'extra',
                'parenthesis_brace_block',
                'return',
                'square_brace_block',
                'throw',
                'use',
                'useTrait',
            ],
            'no_leading_import_slash'         => true,
            'no_leading_namespace_whitespace' => true,
            'no_mixed_echo_print'             => [
                'use' => 'echo',
            ],
            'no_multiline_whitespace_around_double_arrow' => true,
            'no_multiline_whitespace_before_semicolons'   => true,
            'no_php4_constructor'                         => false,
            'no_short_bool_cast'                          => true,
            'no_short_echo_tag'                           => true,
            'no_singleline_whitespace_before_semicolons'  => true,
            'no_spaces_around_offset'                     => true,
            'no_trailing_comma_in_list_call'              => true,
            'no_trailing_comma_in_singleline_array'       => true,
            'no_unneeded_control_parentheses'             => true,
            'no_unreachable_default_argument_value'       => true,
            'no_unused_imports'                           => true,
            'no_useless_else'                             => true,
            'no_useless_return'                           => true,
            'no_whitespace_before_comma_in_array'         => true,
            'no_whitespace_in_blank_line'                 => true,
            'normalize_index_brace'                       => true,
            'not_operator_with_space'                     => false,
            'not_operator_with_successor_space'           => true,
            'object_operator_without_whitespace'          => true,
            'ordered_class_elements'                      => true,
            'ordered_imports'                             => true,
            'php_unit_construct'                          => true,
            'php_unit_dedicate_assert'                    => true,
            'php_unit_fqcn_annotation'                    => true,
            'php_unit_strict'                             => false,
            'phpdoc_add_missing_param_annotation'         => [
                'only_untyped' => false,
            ],
            'phpdoc_align'                  => true,
            'phpdoc_annotation_without_dot' => true,
            'phpdoc_indent'                 => true,
            'phpdoc_inline_tag'             => true,
            'phpdoc_no_access'              => true,
            'phpdoc_no_alias_tag'           => [
                'type' => 'var',
            ],
            'phpdoc_no_empty_return'             => false,
            'phpdoc_no_package'                  => true,
            'phpdoc_order'                       => true,
            'phpdoc_scalar'                      => true,
            'phpdoc_separation'                  => true,
            'phpdoc_single_line_var_spacing'     => true,
            'phpdoc_summary'                     => true,
            'phpdoc_to_comment'                  => true,
            'phpdoc_trim'                        => true,
            'phpdoc_types'                       => true,
            'phpdoc_var_without_name'            => true,
            'pow_to_exponentiation'              => true,
            'pre_increment'                      => true,
            'protected_to_private'               => true,
            'psr0'                               => false,
            'psr4'                               => true,
            'random_api_migration'               => true,
            'return_type_declaration'            => true,
            'self_accessor'                      => false,
            'semicolon_after_instruction'        => true,
            'short_scalar_cast'                  => true,
            'silenced_deprecation_error'         => false,
            'simplified_null_return'             => false,
            'single_blank_line_before_namespace' => false,
            'single_quote'                       => true,
            'space_after_semicolon'              => true,
            'standardize_not_equals'             => true,
            'strict_comparison'                  => false,
            'strict_param'                       => false,
            'ternary_operator_spaces'            => true,
            'trailing_comma_in_multiline_array'  => true,
            'trim_array_spaces'                  => true,
            'unary_operator_spaces'              => true,
            'whitespace_after_comma_in_array'    => true,
        ];

        if ($this->header !== null) {
            $rules['header_comment'] = [
                'header' => $this->header,
            ];
            $rules['no_blank_lines_before_namespace']    = false;
            $rules['single_blank_line_before_namespace'] = true;
        }

        return $rules;
    }

    public function setHeader(string $header)
    {
        $this->header = $header;
    }
}
