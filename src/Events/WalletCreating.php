<?php


namespace Ashrafi\WalletManager\Events;


class WalletCreating extends Event
{
    /**
     * @var array
     */
    public $attributes;

    public function __construct(array $attributes){

        $this->attributes = $attributes;
    }
}
