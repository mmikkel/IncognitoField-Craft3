<?php
/**
 * Incognito Field plugin for Craft CMS 5.x
 *
 * PlainText drop-in replacement that can be set to disabled, hidden or readonly.
 *
 * @link      http://vaersaagod.no
 * @copyright Copyright (c) 2024 Mats Mikkel Rummelhoff
 */

namespace mmikkel\incognitofield;

use mmikkel\incognitofield\fields\IncognitoFieldType;

use craft\base\Plugin;
use craft\services\Fields;
use craft\events\RegisterComponentTypesEvent;

use yii\base\Event;

/**
 * Class IncognitoField
 *
 * @author    Mats Mikkel Rummelhoff
 * @package   IncognitoField
 * @since     1.0.0
 *
 */
class IncognitoField extends Plugin
{

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        
        Event::on(Fields::class, Fields::EVENT_REGISTER_FIELD_TYPES,
            function(RegisterComponentTypesEvent $event) {
                $event->types[] = IncognitoFieldType::class;
            }
        );
    }

}
