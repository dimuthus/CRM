<?php
namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use frontend\modules\tools\models\user\UserPasswordHistory;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $verification_token
 * @property string $email
 * @property string $auth_key
 * @property string $access_token
 * @property integer $status_id
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 */
class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_INACTIVE = 9;
    const STATUS_ACTIVE = 1;
    public $oldpassword;
    public $newpassword;
    public $repeatpassword;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['status_id', 'default', 'value' => self::STATUS_INACTIVE],
            ['status_id', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_INACTIVE, self::STATUS_DELETED]],
			[['newpassword','oldpassword','repeatpassword'],'required'],
            [['oldpassword'],'validateoldpassword'],

            [['newpassword','repeatpassword'], 'string','min'=>8],
			['newpassword', 'match' ,'pattern'=>'/^(?=(.*[a-z]){1,})(?=(.*[\d]){1,})(?=(.*[\W]){1,})(?!.*\s).{8,30}$/u','message'=> 'Password Format is not Correct'],


             ['newpassword', 'match', 'pattern'=>'/\d/', 'message'=>'Password must contain at least one numeric digit.'],

            ['newpassword', 'match', 'pattern'=>'/\W/', 'message'=>'Password must contain at least one special character.'],


            [['newpassword','repeatpassword'], 'filter','filter'=>'trim'],
            [['repeatpassword'],'compare','compareAttribute'=>'newpassword','message'=>'passwords do not match'],  
            [['newpassword'], 'checkpasswordhistory','skipOnEmpty' => false, 'skipOnError' => false],
			
        ];
    }
	

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status_id' => self::STATUS_ACTIVE]);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token, 'status_id' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status_id' => self::STATUS_ACTIVE]);
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
            'status_id' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds user by verification email token
     *
     * @param string $token verify email token
     * @return static|null
     */
    public static function findByVerificationToken($token) {
        return static::findOne([
            'verification_token' => $token,
            'status_id' => self::STATUS_INACTIVE
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
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
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
	 
	  public function validateoldpassword(){
      
        if(!$this->verifypassword($this->oldpassword)){
          $this->addError("password", Yii::t('user', 'Invalid password!! Try again with your correct password.'));
        	 
        }


     }
	 
	 
    public function verifypassword($password)
    {   
      $dbpass=static::findOne(['username'=> Yii::$app->user->identity->username])->password_hash; 
     
       
      return Yii::$app->security->validatePassword($password, $dbpass);

  }
	 
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
     * Generates access_token
     */
    public function generateAccessToken()
    {
        $this->access_token = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    public function generateEmailVerificationToken()
    {
        $this->verification_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }
	
	public function checkpasswordhistory($attribute_name, $params)
    {
		
        //Check Password Validation
        $count=0;
        //$testMessage="";
        //((?=.*\\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^&*]).{8,20})
        // $regex = "/\d+/";
        // if (preg_match($regex, $this->newpassword)) {
            // $count++;
            // $testMessage=$testMessage."Number Found. ";
            //echo "Found a match!";
        // } 
        
        // $regex = "/[a-z]/";
        // if (preg_match($regex, $this->newpassword)) {
            // $count++;            
            //$testMessage=$testMessage."Lowercase Found. ";
        // }
        
        // $regex = "/[A-Z]/";
        // if (preg_match($regex, $this->newpassword)) {
            // $count++;            
           // $testMessage=$testMessage."Uppercase Found. ";
        // }
        
        // $regex = "/[#$%&()*+!@<=>_!]/";
        // if (preg_match($regex, $this->newpassword)) {
            // $count++;            
           // $testMessage=$testMessage."Non-alphabetic character Found. ";
        // }
        
        // $regex = "/^.{8,50}$/s";
        // if (!preg_match($regex, $this->newpassword)) {
           // $this->addError($attribute_name, Yii::t('user', 'Minimum length is 8 characters'));  
                // return false;
        // }
		
		
		// if ($this->newpassword==$this->oldpassword) {
           // $this->addError($attribute_name, Yii::t('user', 'New and old passwords can not be same!'));  
                // return false;
        // }
        
		// die('Check Password History');

		
		
        // if ($count<3){
        // $this->addError($attribute_name, Yii::t('user', 'Password must contain characters from three of the following four categories: <br/>(a) English uppercase characters [A-Z]<br/>(b) English lowercase characters [a-z]<br/>(c) Base 10 digits [0-9]<br/>(d) Non-alphabetic characters [#$%&()*+!@<=>_!]'));  
                // return false;
        // }

        
        //Check Password History
        $result = UserPasswordHistory::find()
                ->where('user_id = :user_id', [':user_id' => Yii::$app->user->identity->id])
                ->andWhere('deleted = :deleted', [':deleted' => 0])
                ->all(); 
     		//die("XXXXXXXXXXXXX=".$result);		
            if( $result){
            for ($x = 0; $x <= count($result)-1; $x++) {
				$ph=$result[$x]->password_hash;
				//$np=$this->newpassword;
				//$nph=$np->password_hash;
				//die($nph. "/".$ph);
                if(Yii::$app->security->validatePassword($this->newpassword,$ph )){
					
				
                $this->addError($attribute_name, Yii::t('user', 'Unable to update the password. The value provided for the new password does not meet the history requirements.'));  
                return false;
                }
            } 
        }
        return true;
    }
}
