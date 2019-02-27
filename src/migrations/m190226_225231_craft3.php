<?php

namespace mmikkel\incognitofield\migrations;

use craft\db\Migration;
use craft\db\Query;
use craft\db\Table;
use craft\helpers\ArrayHelper;
use craft\helpers\Db;
use craft\helpers\Json;
use mmikkel\incognitofield\fields\IncognitoFieldType;
use yii\db\Schema;

/**
 * m190226_225231_craft3 migration.
 */
class m190226_225231_craft3 extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->migratePlainTextSettings();
        $this->update(Table::FIELDS, ['type' => IncognitoFieldType::class], ['type' => 'IncognitoField']);
    }

    /**
     * @throws \yii\base\Exception
     * @see \craft\migrations\m170223_224012_plain_text_settings::safeUp()
     */
    public function migratePlainTextSettings()
    {
        $fields = (new Query())
            ->select(['id', 'settings'])
            ->from([Table::FIELDS])
            ->where(['type' => 'IncognitoField'])
            ->all($this->db);

        foreach ($fields as $field) {
            $settings = Json::decode($field['settings']);

            if (!is_array($settings)) {
                continue;
            }

            // maxLength => charLimit
            ArrayHelper::rename($settings, 'maxLength', 'charLimit');

            // columnType
            if ($settings['charLimit']) {
                // This is how Plain Text fields used to automagically determine their column type
                $settings['columnType'] = Db::getTextualColumnTypeByContentLength($settings['charLimit'], $this->db);
            } else {
                // Default to text
                $settings['columnType'] = Schema::TYPE_TEXT;
            }

            $this->update(Table::FIELDS, [
                'settings' => Json::encode($settings)
            ], ['id' => $field['id']]);
        }
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m190226_225231_craft3 cannot be reverted.\n";
        return false;
    }
}
