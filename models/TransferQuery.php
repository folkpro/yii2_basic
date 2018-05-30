<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[Transfer]].
 *
 * @see Transfer
 */
class TransferQuery extends \yii\db\ActiveQuery
{
    /**
     * {@inheritdoc}
     * @return Transfer
     */
    public function byUser($userId)
    {
        return $this->andWhere(['donor' => $userId]);
    }

    /**
     * {@inheritdoc}
     * @return Transfer[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Transfer|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
