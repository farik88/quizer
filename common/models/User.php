<?php
namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use common\models\SocialAuth;
use yii\web\IdentityInterface;

class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;
    const STATUS_ADMIN = 90;
    const STATUS_SUPERADMIN = 100;


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [
                self::STATUS_ACTIVE,
                self::STATUS_DELETED,
                self::STATUS_ADMIN,
                self::STATUS_SUPERADMIN,
                ]],
        ];
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::find()->where(['id' => $id])->andWhere(['!=', 'status', self::STATUS_DELETED])->limit(1)->one();
    }
    
    public static function findUserByEAuth($service)
    {
        
        if (!$service->getIsAuthenticated()) {
            throw new ErrorException('EAuth user should be authenticated before creating identity.');
        }
        
        $user = null;
        
        $attributes = [
            'network_id' => $service->getId(),
            'network' => $service->getServiceName(),
            'username' => $service->getAttribute('name'),
            'auth_key' => md5($service->getId()),
            'url' => $service->getAttribute('url'),
        ];
       
        $auth = SocialAuth::find()
                ->where(['username' => $attributes['username']])
                ->andWhere(['network' => $attributes['network']])
                ->andWhere(['network_id' => $attributes['network_id']])
                ->andWhere(['auth_key' => $attributes['auth_key']])
                ->andWhere(['username' => $attributes['username']])
                ->limit(1)
                ->one();
        
        if($auth){
            $user_id = $auth->user_id;
            $user = User::findOne($user_id);
        } else {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                //CREATE NEW USER
                $user = new User();
                $user->username = $attributes['network'] . '-' . $attributes['network_id'];
                $user->email = $attributes['network_id'] . '@' . $attributes['network'] . '.mail';
                $user->status = 10;
                $user->setPassword($attributes['auth_key']);
                $user->generateAuthKey();
                $user->save();
                
                //CREATE SOCIAL AUTH FOR NEW USER
                $new_social_auth = new SocialAuth();
                $new_social_auth->user_id = $user->id;
                $new_social_auth->username = $attributes['username'];
                $new_social_auth->auth_key = $attributes['auth_key'];
                $new_social_auth->network = $attributes['network'];
                $new_social_auth->network_id = $attributes['network_id'];
                $new_social_auth->url = $attributes['url'];
                $new_social_auth->save();
                
                $transaction->commit();
            } catch (\Exception $e) {
                $transaction->rollback();
                throw $e->getMessage();
            }
        }
        return $user;
    }
    
    public function getSocialAuth()
    {
        $social_auth = SocialAuth::find()->where(['user_id' => $this->id])->one();
        if($social_auth){
            return $social_auth;
        } else {
            return NULL;
        }
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::find()->where(['username' => $username])->andWhere(['!=', 'status', self::STATUS_DELETED])->limit(1)->one();
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            ['!=', 'status', self::STATUS_DELETED],
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }
    
    /**
     * This function check is that user already add answer for
     * a Quize, wich id function receive
     * @param type $quize_id
     * return boolean true/false
     */
    public function isAlreadyAnswerQuestion($quize_id)
    {
        $user_answer = Answer::find()->where(['user_id' => $this->id])->andWhere(['quize_id' => $quize_id])->limit(1)->one();
        if($user_answer){
            return true;
        } else {
            return false;
        }
    }

    public function beforeDelete()
    {
        if (!parent::beforeDelete()) {
            return false;
        }
        
        if($this->status === $this::STATUS_SUPERADMIN){
            Yii::$app->session->setFlash('error', "You can't delete SUPERADMIN!");
            return false;
        }

        return true;
    }
}
