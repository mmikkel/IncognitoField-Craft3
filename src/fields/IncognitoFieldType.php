<?php

namespace mmikkel\incognitofield\fields;

use Craft;
use craft\base\ElementInterface;
use craft\base\PreviewableFieldInterface;
use craft\fields\PlainText;
use craft\helpers\Html;
use craft\web\View;

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

    public ?string $mode = 'plain';
    public ?string $modeOverride = null;

    private array $_modes;

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

    public function rules(): array
    {
        return array_merge(parent::rules(), [
            [['mode'], 'string'],
            [['mode'], 'default', 'value' => 'plain'],
        ]);
    }

    /**
     * @return string|null
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     * @throws \yii\base\Exception
     */
    public function getSettingsHtml(): ?string
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
     * @param $value
     * @param ElementInterface|null $element
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     * @throws \yii\base\Exception
     */
    public function getInputHtml($value, ?\craft\base\ElementInterface $element = null): string
    {
        $mode = $this->_getMode($element);

        // Get our id and namespace
        if (version_compare(Craft::$app->getVersion(), '3.5', '>=')) {
            $id = Html::id($this->handle);
        } else {
            /** @noinspection PhpDeprecationInspection */
            $id = Craft::$app->getView()->formatInputId($this->handle);
        }

        $namespacedId = Craft::$app->getView()->namespaceInputId($id);

        // Register mode specific CSS and JS
        switch ($mode) {
            case 'hidden':
                $css = <<<CSS
                    #{$namespacedId}-field {
                        display: none;
                    }
                    CSS;
                Craft::$app->getView()->registerCss($css);
                break;

            case 'readonly':
                $js = <<<JS
                    var field = document.getElementById('{$namespacedId}');
                    field.setAttribute('readonly', 'readonly');
                    field.style.background = 'rgb(250,250,250)';
                    field.style.boxShadow = 'none';
                    field.style.opacity = '0.8';
                    field.style.color = '#596473';
                    JS;
                Craft::$app->getView()->registerJs($js, View::POS_END);
                break;
        }

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
        // Return early if there is no override set
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

        // Don’t override if we got an empty string
        if ($modeOverride === '') {
            return $this->mode;
        }

        // Don’t override if we got an invalid value
        if (!array_key_exists($modeOverride, $this->_modes)) {
            Craft::error('Invalid value for mode override: '.$modeOverride, __METHOD__);
            return $this->mode;
        }

        return $modeOverride;
    }
}
