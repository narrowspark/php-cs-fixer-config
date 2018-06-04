<?php
declare(strict_types=1);
namespace Narrowspark\CS\Config;

use Localheinz\PhpCsFixer\Config\RuleSet\Php71;
use PhpCsFixer\Config as CsConfig;

class Config extends CsConfig
{
    /**
     * A instance of the php 7.1 rule set.
     *
     * @var Php71
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

        $this->ruleSet        = new Php71($header);
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
            '@DoctrineAnnotation'                => true,
            'blank_line_after_opening_tag'       => false,
            'no_blank_lines_before_namespace'    => true,
            'single_blank_line_before_namespace' => false,
            'self_accessor'                      => false,
            'no_homoglyph_names'                 => false,
            'not_operator_with_successor_space'  => true,
            'increment_style'                    => [
                'style' => 'post',
            ],
            'phpdoc_no_empty_return'   => false,
            'phpdoc_to_return_type'    => false,
            'blank_line_before_return' => true,
            'date_time_immutable'      => false,
            'yoda_style'               => false,
            'no_unset_on_property'     => false,
            'error_suppression'        => [
                'mute_deprecation_error' => true,
                'noise_remaining_usages' => false,
            ],
            'no_superfluous_phpdoc_tags'                => false,
        ];

        return \array_merge(
            $this->ruleSet->rules(),
            $overrideRules,
            $this->overwriteRules
        );
    }
}
