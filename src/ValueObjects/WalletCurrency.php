<?php
/**
 * Created by PhpStorm.
 * User: ashrafimanesh@gmail.com
 */

namespace Ashrafi\WalletManager\ValueObjects;


class WalletCurrency
{
    /**
     * @var null|string
     */
    public $symbol;

    /**
     * @var null|int
     */
    public $currency_id;

    /**
     * @var double
     */
    public $balance;

    public function __construct($symbol = null, $currency_id = null, $balance = 0)
    {
        $this->symbol = $symbol;
        $this->currency_id = $currency_id;
        $this->balance = $balance;
    }
}