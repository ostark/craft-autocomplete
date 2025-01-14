<?php
/**
 * Autocomplete plugin for Craft CMS 3.x
 *
 * Provides Twig template IDE autocomplete of Craft CMS & plugin variables
 *
 * @link      https://nystudio107.com
 * @link      https://putyourlightson.com
 * @copyright Copyright (c) 2021 nystudio107
 * @copyright Copyright (c) 2021 PutYourLightsOn
 */

namespace nystudio107\autocomplete\generators;

use nystudio107\autocomplete\base\Generator;

use Craft;
use craft\web\twig\variables\CraftVariable;

/**
 * @author    nystudio107
 * @package   autocomplete
 * @since     1.0.0
 */
class AutocompleteVariableGenerator extends Generator
{
    // Public Static Methods
    // =========================================================================

    /**
     * @inheritDoc
     */
    public static function getGeneratorName(): string
    {
        return 'AutocompleteVariable';
    }

    /**
     * @inheritDoc
     */
    public static function generate()
    {
        if (self::shouldRegenerateFile()) {
            static::generateInternal();
        }
    }

    /**
     * @inheritDoc
     */
    public static function regenerate()
    {
        static::generateInternal();
    }

    // Private Static Methods
    // =========================================================================

    /**
     * Core function that generates the autocomplete class
     */
    private static function generateInternal()
    {
        $values = [];
        /* @noinspection PhpInternalEntityUsedInspection */
        $globals = Craft::$app->view->getTwig()->getGlobals();
        /* @var CraftVariable $craftVariable */
        if (isset($globals['craft'])) {
            $craftVariable = $globals['craft'];
            foreach ($craftVariable->getComponents() as $key => $value) {
                $type = gettype($value);
                switch ($type) {
                    case 'object':
                        $className = get_class($value);
                        $values[$key] = $className;
                        break;

                    case 'array':
                        if (isset($value['class'])) {
                            $values[$key] = $value['class'];
                        }
                        break;

                    case 'string':
                        $values[$key] = $value;
                        break;
                }
            }
        }

        // Format the line output for each value
        foreach ($values as $key => $value) {
            $values[$key] = ' * @property \\' . $value . ' $' . $key;
        }

        // Save the template with variable substitution
        self::saveTemplate([
            '{{ properties }}' => implode(PHP_EOL, $values),
        ]);
    }
}
