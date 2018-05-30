<?php

namespace tests\models;

use app\models\User;
use app\models\TransferForm;
use app\fixtures\UserFixture;

class TransferTest extends \Codeception\Test\Unit
{
    private $model;

    /**
     * @var \UnitTester
     */
    protected $tester;

    protected function _before()
    {
        $this->model = new TransferForm();

        $this->tester->haveFixtures([
            'users' => [
                'class' => UserFixture::className(),
                'dataFile' => codecept_data_dir() . 'user.php'
            ]
        ]);
    }

    public function testEmptyForm()
    {
        expect_not($this->model->send());
    }

    public function testEmptyFieldDonor()
    {
        $this->model->attributes = [
            'donor' => '',
            'acceptor' => 'userAcceptorTestFixture',
            'amount' => 10,
        ];

        expect_not($this->model->send());
    }

    public function testEmptyFieldAcceptor()
    {
        $this->model->attributes = [
            'donor' => 'userDonorTestFixture',
            'acceptor' => '',
            'amount' => 10,
        ];

        expect_not($this->model->send());
    }

    public function testEmptyFieldAmount()
    {
        $this->model->attributes = [
            'donor' => 'userDonorTestFixture',
            'acceptor' => 'userAcceptorTestFixture',
            'amount' => '',
        ];

        expect_not($this->model->send());
    }

    public function testNotFoundAcceptor()
    {
        $this->model->attributes = [
            'donor' => 'userDonorTestFixture',
            'acceptor' => '111_userAcceptorTestFixture_245',
            'amount' => 10,
        ];

        expect_not($this->model->send());
    }

    public function testOnlyPositiveAmount()
    {
        $this->model->attributes = [
            'donor' => 'userDonorTestFixture',
            'acceptor' => 'userAcceptorTestFixture',
            'amount' => -10,
        ];

        expect_not($this->model->send());
    }

    public function testNotSendToYourself()
    {
        $this->model->attributes = [
            'donor' => 'userDonorTestFixture',
            'acceptor' => 'userDonorTestFixture',
            'amount' => 10,
        ];

        expect_not($this->model->send());
    }

    public function testMinimumBalance()
    {
        $this->model->attributes = [
            'donor' => 'userDonorTestFixture',
            'acceptor' => 'userAcceptorTestFixture',
            'amount' => abs(TransferForm::MIN_AMOUNT) + 1,
        ];

        expect_not($this->model->send());
    }

    public function testSuccessfulTransferMoney()
    {
        $this->model->attributes = [
            'donor' => 'userDonorTestFixture',
            'acceptor' => 'userAcceptorTestFixture',
            'amount' => 10,
        ];

        expect_that($this->model->send());
    }

    protected function _after()
    {
        User::deleteAll();
    }
}
