<?php

use yii\db\Migration;

/**
 * Handles the creation of table `user`.
 */
class m180528_071512_create_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('user', [
            'id' => $this->primaryKey()->comment('ID'),
            'username' => $this->string()->notNull()->unique()->comment('Username'),
            'amount' => $this->decimal(10, 2)->defaultValue(0)->comment('Amount'),
        ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('user');
    }
}
