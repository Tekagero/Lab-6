<?php

namespace App\Validation;

class EnterValidation
{
    private $email;
    private $password;
    private $email_pattern = "/^((([0-9A-Za-z]{1}[-0-9A-z\.]{1,}[0-9A-Za-z]{1})|([0-9А-Яа-я]{1}[-0-9А-я\.]{1,}[0-9А-Яа-я]{1}))@([-0-9A-Za-z]{1,}\.){1,2}[-A-Za-z]{2,})$/";
    private $password_pattern = "/^.{5,100}[\da-z_-]*[a-z_-][\da-z_-]*$/";

    private $errors = [];

    public function __construct($data)
    {
        $this->email = $data['email'];
        $this->password = $data['password'];
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function isValid()
    {
        return $this->validateEmail() && $this->validatePassword();
    }

    public function validateEmail()
    {
        if(empty($this->email)) {
            array_push($this->errors, 'Email is empty');
            return false;
        }
        if(!preg_match($this->email_pattern, $this->email)) {
            array_push($this->errors, 'Email does not match the pattern');
            return false;
        }
        return true;
    }

    public function validatePassword()
    {
        if(empty($this->password)) {
            array_push($this->errors, 'Password is empty');
            return false;
        }
        if(!preg_match($this->password_pattern,$this->password) && strlen($this->password) > 6) {
            array_push($this->errors, 'Password does not match the pattern');
            return false;
        }
        return true;
    }
}
