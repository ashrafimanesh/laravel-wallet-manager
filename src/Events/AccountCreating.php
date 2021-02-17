<?php


namespace Ashrafi\WalletManager\Events;


class AccountCreating extends Event
{
    /**
     * @var array
     */
    public $attributes;

    public function __construct(array $attributes){

        $this->attributes = $attributes;
    }

}
