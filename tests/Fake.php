<?php

trait Fake
{
    private $faker = 'a';

    function __construct()
    {
        parent::__construct();
        $this->faker = Faker\Factory::create('pt_BR');
    }

    public function fakeName()
    {
        return $this->faker->name();
    }

    public function fakeEmail()
    {
        return $this->faker->email();
    }

    public function fakePassword()
    {
        return $this->faker->password();
    }

    public function fakeCpf()
    {
        return $this->faker->cpf(false);
    }

    public function fakeDigit()
    {
        return $this->faker->randomDigitNotNull();
    }

}
