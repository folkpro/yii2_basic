<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "transfer".
 *
 * @property int $id ID
 * @property int $donor Donor
 * @property int $acceptor Acceptor
 * @property string $amount Amount
 * @property string $date_create Date Create
 *
 * @property User $getAcceptorUser
 * @property User $getDonorUser
 */
class Transfer extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'transfer';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['donor', 'acceptor', 'amount'], 'required'],
            [['donor', 'acceptor'], 'integer'],
            [['amount'], 'number'],
            [['date_create'], 'safe'],
            [['acceptor'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['acceptor' => 'id']],
            [['donor'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['donor' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'donor' => Yii::t('app', 'Donor'),
            'acceptor' => Yii::t('app', 'Acceptor'),
            'amount' => Yii::t('app', 'Amount'),
            'date_create' => Yii::t('app', 'Date Create'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAcceptorUser()
    {
        return $this->hasOne(User::className(), ['id' => 'acceptor']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDonorUser()
    {
        return $this->hasOne(User::className(), ['id' => 'donor']);
    }

    /**
     * {@inheritdoc}
     * @return string|null
     */
    public function getAcceptorName()
    {
        return (!empty($this->acceptorUser) ? $this->acceptorUser->username : null);
    }

    /**
     * {@inheritdoc}
     * @return string
     */
    public function setAcceptorName($pr)
    {
        return $pr;
    }

    /**
     * {@inheritdoc}
     * @return TransferQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TransferQuery(get_called_class());
    }
}
