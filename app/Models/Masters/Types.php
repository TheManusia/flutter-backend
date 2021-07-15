<?php

namespace App\Models\Masters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;

/**
 * @mixin Builder
 */
class Types extends Model
{
    protected $table = 'mstype';
    protected $fillable = [
        "parentid",
        "typecd",
        "typenm",
        "typeseq",
        "description"
    ];

    public $timestamps = false;

    public $defaultSelects = array(
        "typecd",
        "typenm",
        "typeseq",
        "description",
    );

    /**
     * @param Relation $query
     * @param array|null $selects
     * @return Relation
     * */
    static public function foreignSelect($query, $selects = null)
    {
        $types = new Types();
        return $types->withJoin(is_null($selects) ? $types->defaultSelects : $selects, $query);
    }

    /**
     * @param Relation|Types $query
     * @param array $selects
     * @return Relation
     * */
    private function _withJoin($query, $selects = array())
    {
        return $query->with([
            'parent' => function($query) {
                Types::foreignSelect($query);
            }
        ])->select('id', 'parentid')->addSelect($selects);
    }

    /**
     * @param array $selects
     * @param Relation|Types
     * @return Relation
     * */
    public function withJoin($selects = array(), $query = null)
    {
        return $this->_withJoin(is_null($query) ? $this : $query, is_array($selects) ? $selects : func_get_args());
    }

    public function parent()
    {
        return $this->hasOne(Types::class, 'id', 'parentid');
    }
}
