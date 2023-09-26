<?php

namespace App\Pattern\Input;

class InputStrategy
{
    private InputInterface $input;

    public function __construct(InputInterface $input)
    {
        $this->input = $input;
    }

    public function create()
    {
        return $this->input->store();
    }
}
