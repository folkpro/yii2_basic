<?php

use yii\db\Migration;

/**
 * Handles the creation of table `transfer`.
 */
class m180528_082237_create_transfer_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('transfer', [
            'id' => $this->primaryKey()->comment('ID'),
            'donor' => $this->integer()->notNull()->comment('Donor'),
            'acceptor' => $this->integer()->notNull()->comment('Acceptor'),
            'amount' => $this->decimal(10, 2)->notNull()->unsigned()->comment('Amount'),
            'date_create' => $this->dateTime()->comment('Date Create'),
        ]);

        $this->addForeignKey(
            'donor',
            'transfer',
            'donor',
            'user',
            'id',
            'CASCADE'
        );
        $this->addForeignKey(
            'acceptor',
            'transfer',
            'acceptor',
            'user',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('transfer');

        $this->dropForeignKey(
            'donor'
        );
        $this->dropForeignKey(
            'acceptor'
        );
    }
}
