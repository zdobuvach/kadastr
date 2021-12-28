<?php

namespace tests\unit\models;

use app\models\CadastralNumbers;

class CadastralNumbersTest extends \Codeception\Test\Unit
{
    public function testFindUserById()
    {
        expect_that($user = User::findIdentity(100));
        expect($user->username)->equals('admin');

        expect_not(User::findIdentity(999));
    }    

}
