<?php


namespace Ashrafi\WalletManager\Models;

use Carbon\Carbon;

/**
 * Class Model
 * @property Carbon|mixed locked_at
 * @property mixed|string locked_description
 * @property mixed locked_by
 * @property int|mixed status
 */
class Model extends \Illuminate\Database\Eloquent\Model
{
    const STATUS_ACTIVE = 1;
    const STATUS_IN_ACTIVE = 0;

    public function setIndexToArrayCast(string $field, $key, $value = null)
    {
        $config = ($this->$field) ?? [];
        $oldValue = isset($config[$key]) ? $config[$key] : null;
        $config[$key] = $value;
        $this->$field = $config;
        return $oldValue;
    }
}
