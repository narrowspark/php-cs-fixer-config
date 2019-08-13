<?php
declare(strict_types=1);
namespace Narrowspark\CS\Config;

use Localheinz\PhpCsFixer\Config\RuleSet\Php71;
use Localheinz\PhpCsFixer\Config\RuleSet\Php73;
use PhpCsFixer\Config as CsConfig;
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

final class Config extends CsConfig
{
    /**
     * A instance of the php 7.1 or 7.3 rule set.
     *
     * @var \Localheinz\PhpCsFixer\Config\RuleSet\Php71|\Localheinz\PhpCsFixer\Config\RuleSet\Php73
     */
    private $ruleSet;

    /**
     * A list of override rules.
     *
     * @var array
     */
    private $overwriteRules;

    /**
     * Create new Config instance.
     *
     * @param null|string $header
     * @param array       $overwriteConfig
     */
    public function __construct(string $header = null, array $overwriteConfig = [])
    {
        parent::__construct('narrowspark');

        $this->setRiskyAllowed(true);
        $this->registerCustomFixers(new \PedroTroller\CS\Fixer\Fixers());
        $this->registerCustomFixers(new \PhpCsFixerCustomFixers\Fixers());

        $this->ruleSet        = \PHP_VERSION_ID >= 70300 ? new Php73($header) : new Php71($header);
        $this->overwriteRules = $overwriteConfig;
    }

    /**
     * @return array
     */
    public function getRules(): array
    {
        $overrideRules = [
            'binary_operator_spaces' => [
                'default' => 'align',
            ],
            '@DoctrineAnnotation'                     => true,
            'blank_line_after_opening_tag'            => false,
            'no_blank_lines_before_namespace'         => true,
            'single_blank_line_before_namespace'      => false,
            'self_accessor'                           => false,
            'native_function_type_declaration_casing' => true,
            'no_homoglyph_names'                      => false,
            'not_operator_with_successor_space'       => true,
            'increment_style'                         => [
                'style' => 'post',
            ],
            'mb_str_functions'         => false,
            'phpdoc_no_empty_return'   => false,
            'phpdoc_to_return_type'    => true,
            'comment_to_phpdoc'        => false,
            'blank_line_before_return' => true,
            'date_time_immutable'      => false,
            'yoda_style'               => false,
            'no_unset_on_property'     => false,
            'error_suppression'        => [
                'mute_deprecation_error' => true,
                'noise_remaining_usages' => false,
            ],
            'no_superfluous_phpdoc_tags'             => false,
            'fopen_flags'                            => false,
            'fopen_flag_order'                       => false,
            'php_unit_test_class_requires_covers'    => false,
        ];

        $pedroTrollerRules = [
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

        $kubawerlosRules = [
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

        return \array_merge(
            $this->ruleSet->rules(),
            $overrideRules,
            $pedroTrollerRules,
            $kubawerlosRules,
            $this->overwriteRules
        );
    }
}
