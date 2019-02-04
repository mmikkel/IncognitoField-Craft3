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
    // Properties
    // =========================================================================

    public $mode = 'plain';
    public $modeOverride;

    private $_modes;

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

    public function __construct(array $config = [])
    {
        $this->_modes = [
            'plain' => Craft::t('site', 'Plain Text'),
            'disabled' => Craft::t('site', 'Disabled'),
            'hidden' => Craft::t('site', 'Hidden'),
            'readonly' => Craft::t('site', 'Read-only'),
        ];

        parent::__construct($config);
    }

    public function rules()
    {
        $rules = array_merge(parent::rules(), [
            [['mode'], 'string'],
            [['mode'], 'default', 'value' => 'plain'],
        ]);
        return $rules;
    }

    /**
     * @return string
     */
    public function getSettingsHtml()
    {
        // Render the settings template
        return Craft::$app->getView()->renderTemplate(
            'incognito-field/_components/fields/_settings',
            [
                'field' => $this,
                'modes' => $this->_modes,
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
        $mode = $this->_getMode($element);

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
                'mode' => $mode,
            ]
        );
    }

    // Private Methods
    // =========================================================================

    /**
     * @param ElementInterface|null $element
     * @return string
     * @throws \yii\base\Exception
     */
    private function _getMode(ElementInterface $element = null): string
    {
        if (empty($this->modeOverride)) {
            return $this->mode;
        }

        $view = Craft::$app->getView();
        $oldTemplateMode = $view->getTemplateMode();
        $view->setTemplateMode($view::TEMPLATE_MODE_SITE);

        try {
            $modeOverride = $view->renderString($this->modeOverride, ['element' => $element]);
            $modeOverride = trim($modeOverride);
        } catch (\Exception $e) {
            Craft::error('Couldn’t render mode override template. '.$e->getMessage(), __METHOD__);
            $view->setTemplateMode($oldTemplateMode);
            return $this->mode;
        }

        $view->setTemplateMode($oldTemplateMode);

        if (!array_key_exists($modeOverride, $this->_modes)) {
            Craft::error('Invalid value for mode override: '.$modeOverride, __METHOD__);
            return $this->mode;
        }

        return $modeOverride;
    }
}
