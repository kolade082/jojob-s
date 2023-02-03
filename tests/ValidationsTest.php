<?php

namespace tests;
use CSY2038\Controllers\Validations;
use PHPUnit\Framework\TestCase;

class ValidationsTest extends TestCase
{
    private $validator;

    public function testValidateloginForm()
    {
        $username = "johndoe";
        $password = "password";

        $result = $this->validator->validateloginForm($username, $password);

        $this->assertEmpty($result, 'All required fields are filled');
    }
    public function testloginEmptyForm()
    {
        $username = '';
        $password = '';

        $result = $this->validator->validateloginForm($username, $password);

        $this->assertCount(2, $result);
    }

    public function testValidateRegisterForm()
    {
        $fullname = "John Doe";
        $username = "johndoe";
        $password = "password";
        $usertype = "admin";

        $result = $this->validator->validateRegisterForm($fullname, $username, $password, $usertype);

        $this->assertEmpty($result, 'All required fields are filled');
    }
    public function testRegisterFieldsEmpty()
    {
        $fullname = '';
        $username = '';
        $password = '';
        $usertype = '';

        $result = $this->validator->validateRegisterForm($fullname, $username, $password, $usertype);
        $this->assertCount(4, $result);
    }


    protected function setUp(){
        $this->validator = new Validations();
    }
}
