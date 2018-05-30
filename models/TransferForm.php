<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * TransferForm is the model behind the transfer form.
 */
class TransferForm extends Model
{
    public $donor;
    public $acceptor;
    public $amount;
    const MIN_AMOUNT = -1000;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['donor', 'acceptor', 'amount'], 'required'],
            [['donor', 'acceptor'], 'string', 'max' => 60],
            [['acceptor'], 'checkAcceptor'],
            [['amount'], 'number'],
            [['amount'], 'checkMinimumBalance'],
            [['amount'], 'compare', 'compareValue' => 0.01, 'operator' => '>='],
        ];
    }

    /**
     * Acceptor validation by User model
     *
     * @return  mixed
     */
    public function checkAcceptor($attribute, $params)
    {
        $acceptor = User::findByUsername($this->acceptor);
        if($acceptor == null) {
            $this->addError($attribute, 'This user does not exist.');
        } elseif ($acceptor->username == $this->donor) {
            $this->addError($attribute, 'You can not transfer money to yourself');
        }
    }

    /**
     * Minimum balance validation by User model
     *
     * @return  mixed
     */
    public function checkMinimumBalance($attribute, $params)
    {
        $donor = User::findByUsername($this->donor);
        if($donor && $donor->amount - $this->amount < self::MIN_AMOUNT) {
            $this->addError($attribute, 'You do not have enough funds in your account');
        }
    }

    /**
     * Transfer money from donor to acceptor.
     *
     * @return boolean
     */
    public function send()
    {
        if ($this->validate()) {
            $transaction = Yii::$app->db->beginTransaction();

            $donor = User::findByUsername($this->donor);
            $donor->amount = $donor->amount - $this->amount;

            $acceptor = User::findByUsername($this->acceptor);
            $acceptor->amount = $acceptor->amount + $this->amount;

            $transfer = new Transfer;
            $transfer->donor = $donor->id;
            $transfer->acceptor = $acceptor->id;
            $transfer->amount = $this->amount;
            $transfer->date_create = date("Y-m-d H:i:s", time());

            if ($donor->save() && $acceptor->save() && $transfer->save()) {
                $transaction->commit();
                return true;
            } else {
                $transaction->rollback();
                return false;
            }
        }
        return false;
    }
}
