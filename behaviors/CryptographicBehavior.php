<?php
namespace cryptography\behaviors;

use yii\db\ActiveRecord;
use yii\base\Event;
use yii\base\Behavior;
use cryptography\components\Cipher;
use yii\base\InvalidConfigException;
use Yii;

/**
 * Auto encrypt and decrypt behaviour
 *   'encryption' => [
 *       'class' => 'app\behaviors\CryptographicBehavior',
 *       'attributes' => [
 *           'column1',
 *           'column2',
 *          ],
 *    ],
 */
class CryptographicBehavior extends Behavior
{
    public $attributes = [];

    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_FIND => 'decryptAttributes',
            ActiveRecord::EVENT_BEFORE_INSERT => 'encryptAttributes',
            ActiveRecord::EVENT_BEFORE_UPDATE => 'encryptAttributes',
            ActiveRecord::EVENT_AFTER_INSERT => 'decryptAttributes',
            ActiveRecord::EVENT_AFTER_UPDATE => 'decryptAttributes',
        ];
    }

    /**
     * Decrypts all loaded attributes(columns) loop thouth each attributes
     */
    public function decryptAttributes(Event $event)
    {
        foreach ($this->attributes as $attribute) {
            $this->decrypt($attribute);
        }
    }

    /**
     * Encrypts all loaded attributes(columns) loop thouth each attributes
     */
    public function encryptAttributes(Event $event)
    {
        foreach ($this->attributes as $attribute) {
             $this->encrypt($attribute);
        }
    }

    /**
     * Decrypt single attribute.
     */
    private function decrypt($attribute)
    {
        $this->owner->$attribute = $this->getCryptoComponent()->decrypt($this->owner->$attribute);
    }

    /**
     * Encrypts single attribute.
     */
    private function encrypt($attribute)
    {
        $this->owner->$attribute = $this->getCryptoComponent()->encrypt($this->owner->$attribute); 
    }

    /**
     * load component class
     */
    private function getCryptoComponent()
    {
        try {
            return \Yii::$app->crypto;
        } catch (\Exception $exc) {
            throw new InvalidConfigException('Crypto component not enabled.');
        }        
    }

}
