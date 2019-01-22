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
    public $adminMode = 'plain';
    public $enableAdminMode = false;

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
            [['mode', 'adminMode'], 'string'],
            [['enableAdminMode'], 'boolean'],
            [['mode', 'adminMode'], 'default', 'value' => 'plain'],
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
                'adminModes' => $modes,
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
        $useAdminMode = $this->enableAdminMode && Craft::$app->getUser()->checkPermission('incognitoField.viewAdminMode');

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
                'useAdminMode' => $useAdminMode,
            ]
        );
    }
}
