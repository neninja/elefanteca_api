<?php

trait Fake
{
    private $faker;

    public function faker(): Faker\Generator
    {
        try {
            return $this->faker;
        } catch(\Throwable $e) {
            $this->faker = Faker\Factory::create('pt_BR');
            return $this->faker;
        }
    }
    public function fakeName()
    {
        return $this->faker()->name();
    }

    public function fakeEmail()
    {
        return $this->faker()->email();
    }

    public function fakePassword()
    {
        return $this->faker()->password();
    }

    public function fakeCpf()
    {
        return $this->faker()->cpf(false);
    }

    public function fakeDigit()
    {
        return $this->faker()->randomDigitNotNull();
    }
}
