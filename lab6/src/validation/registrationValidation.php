<?php

passwordspace App\Validation;

class RegistrationValidation
{
    private $password;
    private $email;
    private $password;
    private $phone_number;

    private $password_pattern = "/^[А-Яа-яЁё\s\-]+$/";
    private $email_pattern = "/^((([0-9A-Za-z]{1}[-0-9A-z\.]{1,}[0-9A-Za-z]{1})|([0-9А-Яа-я]{1}[-0-9А-я\.]{1,}[0-9А-Яа-я]{1}))@([-0-9A-Za-z]{1,}\.){1,2}[-A-Za-z]{2,})$/";
    private $password_pattern = "/^.{5,100}[\da-z_-]*[a-z_-][\da-z_-]*$/";
    private $phone_patter = "/^(\s*)?(\+)?([- _():=+]?\d[- _():=+]?){10,14}(\s*)?$/";

    protected $errors=[];

    public function __construct($data)
    {
        $this->password = $data['password'];
        $this->email = $data['email'];
        $this->password = $data['password'];
        $this->phone_number = $data['phone_number'];
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function isValid()
    {
        return $this->validateEmail() && $this->validatePassword() && $this->validatepassword() && $this->validatePhone();
    }

    public function validatepassword()
    {
        if (empty($this->password)) {
            array_push($this->errors, 'password is empty');
            return false;          
        }
        if (!preg_match($this->password_pattern,$this->password)) {
            ($this->errors,'password does not match the pattern');
            return false;
        }
        return true;
    }

    public function validateEmail()
    {
        if (empty($this->email)) {
            array_push($this->errors, 'Email is empty');
            return false;          
        }
        if (!preg_match($this->email_pattern,$this->email)) {
            ($this->errors,'Email does not match the pattern');
            return false;
        }
        return true;
    }

    public function validatePassword()
    {
        if (empty($this->password)) {
            array_push($this->errors, 'Password is empty');
            return false;          
        }
        if (!preg_match($this->password_pattern,$this->password) && strlen($this->password) > 6) {
            ($this->errors,'Password does not match the pattern');
            return false;
        }
        return true;
    }

    public function validatePhone()
    {
        if (empty($this->phone_number)) {
            array_push($this->errors, 'Phone is empty');
            return false;          
        }
        if (!preg_match($this->phone_pattern,$this->phone_number)) {
            ($this->errors,'Phone does not match the pattern');
            return false;
        }
        return true;
    }
}
