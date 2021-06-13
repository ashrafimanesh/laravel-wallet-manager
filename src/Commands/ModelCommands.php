<?php


namespace Ashrafi\WalletManager\Commands;


use Ashrafi\WalletManager\Contracts\iUser as User;
use Ashrafi\WalletManager\Models\Model;
use Carbon\Carbon;

class ModelCommands
{

    public function active(Model $model, $save = false){
        $model->status = Model::STATUS_ACTIVE;
        $model->locked_at = null;
        $success = false;
        if($save){
            $success = $model->save();
        }
        return $success;
    }

    public function lock(Model  $model, $lockedBy=null, $lockDescription = '', $save = false){
        $model->locked_at = Carbon::now();
        $model->locked_description = $lockDescription;
        if($lockedBy){
            $model->locked_by = ($lockedBy instanceof User) ? $lockedBy->getKey() : $lockedBy;
        }
        $success = true;
        if($save){
            $success = $model->save();
        }
        return $success;
    }
}
