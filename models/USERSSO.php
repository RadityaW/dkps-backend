<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "USER_SSO".
 *
 * @property string $USER_ID
 * @property string $USERNAME
 * @property string $PASSWORD
 * @property int $ACTIVE
 * @property string $EMAIL
 * @property string $DTCREA
 * @property string $LEVEL_ID
 * @property string $NAMAUSER
 * @property string $WIL
 * @property string $NMWIL
 * @property string $LAP
 * @property string $NIP
 * @property string $JABATAN
 * @property string $IDPEG
 * @property int $STUSER
 */
class USERSSO extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{
    public $auth_key;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'USER_SSO';
    }

    public static function primaryKey()
    {
        return array('USER_ID');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['USER_ID', 'LEVEL_ID', 'WIL', 'LAP'], 'number'],
            [['ACTIVE', 'STUSER'], 'integer'],
            [['USERNAME', 'PASSWORD', 'EMAIL'], 'string', 'max' => 150],
            [['DTCREA'], 'string', 'max' => 7],
            [['NAMAUSER', 'NMWIL'], 'string', 'max' => 300],
            [['NIP', 'IDPEG'], 'string', 'max' => 54],
            [['JABATAN'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'USER_ID' => Yii::t('app', 'User  ID'),
            'USERNAME' => Yii::t('app', 'Username'),
            'PASSWORD' => Yii::t('app', 'Password'),
            'ACTIVE' => Yii::t('app', 'Active'),
            'EMAIL' => Yii::t('app', 'Email'),
            'DTCREA' => Yii::t('app', 'Dtcrea'),
            'LEVEL_ID' => Yii::t('app', 'Level  ID'),
            'NAMAUSER' => Yii::t('app', 'Namauser'),
            'WIL' => Yii::t('app', 'Wil'),
            'NMWIL' => Yii::t('app', 'Nmwil'),
            'LAP' => Yii::t('app', 'Lap'),
            'NIP' => Yii::t('app', 'Nip'),
            'JABATAN' => Yii::t('app', 'Jabatan'),
            'IDPEG' => Yii::t('app', 'Idpeg'),
            'STUSER' => Yii::t('app', 'Stuser'),
        ];
    }
    
    public function getId() {
        return $this->USER_ID;
    }

    public static function findIdentity($id) {
        return static::findOne($id);
    }

    public function validateAuthKey($authKey) {
        return $this->getAuthKey() === $authKey;
    }

    public function getAuthKey() {
        return $this->auth_key;
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        foreach (self::$users as $user) {
            if ($user['accessToken'] === $token) {
                return new static($user);
            }
        }

        return null;
    }


    public static function findByUsername($username) {
        return static::findOne(['USERNAME' => $username]);
    }

    public function validatePassword($password)
    {
        // var_dump(MD5($password));exit();
        return $this->PASSWORD === MD5($password);
    }
}
