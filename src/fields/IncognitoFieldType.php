<?php
/**
 * Incognito Field plugin for Craft CMS 3.x
 *
 * @link      https://www.vaersaagod.no
 * @copyright Copyright (c) 2017 Mats Mikkel Rummelhoff
 */

namespace mmikkel\incognitofield\fields;

use Craft;
use craft\base\ElementInterface;
use craft\base\PreviewableFieldInterface;
use craft\fields\PlainText;

/**
 * Incognito field type
 *
 * @author    Mats Mikkel Rummelhoff
 * @package   IncognitoField
 * @since     1.0.0
 *
 */
class IncognitoFieldType extends PlainText implements PreviewableFieldInterface
{
    // Public Properties
    // =========================================================================
    public $mode = 'plain';

    // Static Methods
    // =========================================================================

    /**
     * Returns the display name of this class.
     *
     * @return string The display name of this class.
     */
    public static function displayName(): string
    {
        return Craft::t('incognito-field', 'Incognito Field');
    }

    // Public Methods
    // =========================================================================

    public function rules()
    {
        $rules = array_merge(parent::rules(), [
            ['mode', 'string'],
            ['mode', 'default', 'value' => 'plain'],
        ]);
        return $rules;
    }

    /**
     * @return string
     */
    public function getSettingsHtml()
    {

        $modes = [
            'plain' => Craft::t('site', 'Plain Text'),
            'disabled' => Craft::t('site', 'Disabled'),
            'hidden' => Craft::t('site', 'Hidden'),
            'readonly' => Craft::t('site', 'Read-only'),
        ];

        // Render the settings template
        return Craft::$app->getView()->renderTemplate(
            'incognito-field/_components/fields/_settings',
            [
                'field' => $this,
                'modes' => $modes,
            ]
        );
    }

    /**
     * @param mixed $value
     * @param ElementInterface|null $element
     *
     * @return string
     */
    public function getInputHtml($value, ElementInterface $element = null): string
    {

        // Get our id and namespace
        $id = Craft::$app->getView()->formatInputId($this->handle);
        $namespacedId = Craft::$app->getView()->namespaceInputId($id);

        // Render the input template
        return Craft::$app->getView()->renderTemplate(
            'incognito-field/_components/fields/_input',
            [
                'name' => $this->handle,
                'value' => $value,
                'field' => $this,
                'id' => $id,
                'namespacedId' => $namespacedId,
            ]
        );
    }
}
