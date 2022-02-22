<?php

namespace Core\Models;

use Core\Exceptions\ValidationException;

class Email
{
    private string $email;

    public function __construct($email)
    {
        $this->setEmail($email);
    }

    public function getEmail() {
        return $this->email;
    }

    public function setEmail(string $email) {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new ValidationException("Email inválido", $email);
        }
        $this->email = $email;
        return $this;
    }
}
