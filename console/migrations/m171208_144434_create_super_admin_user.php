<?php

use yii\db\Migration;
use common\models\User;
use yii\db\Query;

/**
 * Class m171208_144434_create_super_admin_user
 */
class m171208_144434_create_super_admin_user extends Migration
{
    private $admin_username = 'admin';
    private $admin_email = 'email@admin.com';
    private $admin_password = 'secret';
    private $admin_status = 100;
    /**
     * @inheritdoc
     */
    public function up()
    {
        $user = new User();
        $user->username = $this->admin_username;
        $user->email = $this->admin_email;
        $user->status = $this->admin_status;
        $user->setPassword($this->admin_password);
        $user->generateAuthKey();
        $user->save();
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $query = new Query;
        $query->createCommand()->delete('user', 'status = :status AND username = :username AND email = :email', [
            ':status' => $this->admin_status,
            ':username' => $this->admin_username,
            ':email' => $this->admin_email,
        ])->execute();
    }
}
