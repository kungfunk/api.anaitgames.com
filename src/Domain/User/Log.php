<?php
namespace Domain\User;

use Illuminate\Database\Eloquent\Model as Model;

class Log extends Model
{
    const FIXED_ORDER = 'timestamp';

    public function user() {
        return $this->belongsTo('Domain\User\User');
    }
}