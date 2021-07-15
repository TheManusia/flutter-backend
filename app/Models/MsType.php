<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin Builder
 */
class MsType extends Model
{
    protected $table = 'mstype';
    public $timestamps = false;

    public function parent() {
        return $this->belongsTo(MsType::class, 'parentid');
    }
}
