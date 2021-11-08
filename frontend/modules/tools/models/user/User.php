<?php

namespace frontend\modules\tools\models\user;
use frontend\modules\tools\models\user\UserPasswordHistory;
use Yii;

/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property string $username
 * @property string $auth_key
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property integer $status_id
 * @property integer $created_by
 * @property integer $creation_datetime
 * @property integer $last_modified_datetime
 * @property string $first_name
 * @property string $last_name
 * @property integer $role_id 
 * @property string $passwordupdate_datetime
 * @property integer $firsttime
 */
class User extends \yii\db\ActiveRecord
{

    public $repeatpassword;
    public $oldpassword;
    public $newpassword;
    public $default_password = 'P@ssword1';

    /**
     * @inheritdoc
     */

    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'email', 'first_name', 'last_name'], 'filter', 'filter'=>'trim'],
            [['email', 'first_name', 'last_name', 'role_id'], 'required', 'message'=>''],
            [['username'], 'required', 'message'=>'','on'=>'create'],
            ['first_name', 'match' ,'pattern'=>'/^[A-Za-z ]+$/u','message'=> 'First name must be an alphabetic value.'],
            ['last_name', 'match' ,'pattern'=>'/^[A-Za-z ]+$/u','message'=> 'Last name must be an alphabetic value.'],
            ['email', 'email', 'message'=>'Email address is invalid.'],
            [['status_id'], 'integer'],
            [['username', 'password_hash', 'email', 'first_name', 'last_name'], 'string', 'max' => 255],
            [['role_id'], 'string', 'max' => 85],
            
            [['password_hash', 'repeatpassword'], 'required', 'on'=>'create','message'=>''],
            
            //['password_hash', 'match' ,'pattern'=>'/^[A-Za-z0-9]{6,}$/u','message'=> '', 'on'=>'create'],
            ['repeatpassword', 'compare', 'compareAttribute'=>'password_hash', 'message'=>"Passwords do not match.", 'on'=>'create'],
			[[ 'username'], 'unique', 'targetAttribute' => ['username'], 'message' => 'Duplicate username'],

            ['password_hash', 'required', 'on'=>'resetpassword','message'=>''],

            [['newpassword', 'repeatpassword', 'oldpassword'], 'required', 'on'=>'changepassword','message'=>''],
            //['newpassword', 'match' ,'pattern'=>'((?=.*\\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^&*]).{8,20})','message'=> 'Password Format is not Correct.', 'on'=>'changepassword'],
            ['repeatpassword', 'compare', 'compareAttribute'=>'newpassword', 'message'=>"Passwords do not match.", 'on'=>['changepassword']],
            ['newpassword', 'compare', 'compareAttribute'=>'oldpassword', 'operator' => '!=', 'message'=>"New password cannot be same as before.", 'on'=>['changepassword']],
            [['oldpassword'], 'required', 'on'=>'changepassword','message'=>'Incorrect Current password'],
            [['oldpassword'], 'checkpassword', 'on'=>'changepassword', 'skipOnEmpty' => false, 'skipOnError' => false],
            [['newpassword'], 'checkpasswordhistory', 'on'=>'changepassword', 'skipOnEmpty' => false, 'skipOnError' => false],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'auth_key' => 'Auth Key',
            'password_hash' => 'Password',
            'repeatpassword' => 'Confirm Password',
            'oldpassword' => 'Current Password',
            'newpassword' => 'New Password',
            'password_reset_token' => 'Password Reset Token',
            'email' => 'Email',
            'status_id' => 'Status',
            'creation_datetime' => 'Creation Date & Time',
            'last_modified_datetime' => 'Last Update Date & Time',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'role_id' => 'Role',
            'status.name' => 'Status',
            'creator.username' => 'Created by',
            'full_name' => Yii::t('app', 'Full Name')
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStatus()
    {
        return $this->hasOne(UserStatus::className(), ['id' => 'status_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreator()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    public function getFull_name() 
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function beforeSave($options = array()) {
        if($this->scenario == 'create' || $this->scenario == 'resetpassword')
            $this->password_hash = Yii::$app->security->generatePasswordHash($this->password_hash);
        elseif($this->scenario == 'changepassword')
            $this->password_hash = Yii::$app->security->generatePasswordHash($this->newpassword);
        return true;
    }

    public function checkpassword($attribute_name, $params)
    {
        if (Yii::$app->security->validatePassword($this->$attribute_name,$this->password_hash)) {
            return true;
        }
        $this->addError($attribute_name, Yii::t('user', 'Incorrect Current password'));
            
        return false;
    }
    public function checkpasswordhistory($attribute_name, $params)
    {
        //Check Password Validation
        $count=0;
        //$testMessage="";
        //((?=.*\\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^&*]).{8,20})
        $regex = "/\d+/";
        if (preg_match($regex, $this->newpassword)) {
            $count++;
            //$testMessage=$testMessage."Number Found. ";
            //echo "Found a match!";
        } 
        
        $regex = "/[a-z]/";
        if (preg_match($regex, $this->newpassword)) {
            $count++;            
            //$testMessage=$testMessage."Lowercase Found. ";
        }
        
        $regex = "/[A-Z]/";
        if (preg_match($regex, $this->newpassword)) {
            $count++;            
            //$testMessage=$testMessage."Uppercase Found. ";
        }
        
        $regex = "/[#$%&()*+!@<=>_!]/";
        if (preg_match($regex, $this->newpassword)) {
            $count++;            
            //$testMessage=$testMessage."Non-alphabetic character Found. ";
        }
        
        $regex = "/^.{8,50}$/s";
        if (!preg_match($regex, $this->newpassword)) {
           $this->addError($attribute_name, Yii::t('user', 'Minimum length is 8 characters'));  
                return false;
        }
        
		
        if ($count<3){
        $this->addError($attribute_name, Yii::t('user', 'Password must contain characters from three of the following four categories: <br/>(a) English uppercase characters [A-Z]<br/>(b) English lowercase characters [a-z]<br/>(c) Base 10 digits [0-9]<br/>(d) Non-alphabetic characters [#$%&()*+!@<=>_!]'));  
                return false;
        }
        
        
        //Check Password History
        $result = UserPasswordHistory::find()
                ->where('user_id = :user_id', [':user_id' => Yii::$app->user->identity->id])
                ->andWhere('deleted = :deleted', [':deleted' => 0])
                ->all();        
        if($result){
            for ($x = 0; $x <= count($result)-1; $x++) {
                if(Yii::$app->security->validatePassword($this->newpassword, $result[$x]->password_hash)){
                $this->addError($attribute_name, Yii::t('user', 'Unable to update the password. The value provided for the new password does not meet the history requirements.'));  
                return false;
                }
            } 
        }
        return true;
    }

}
