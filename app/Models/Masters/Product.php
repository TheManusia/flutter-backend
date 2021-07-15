<?php

namespace App\Models\Masters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;

/**
 * @mixin Builder
 */
class Product extends Model
{
    protected $table = 'msproduct';
    protected $fillable = [
        "typeid",
        "productcd",
        "productnm",
        "description"
    ];

    public $timestamps = false;

    public $defaultSelects = array(
        "productcd",
        "productnm",
        "description",
    );

    /**
     * @param Relation $query
     * @param array|null $selects
     * @return Relation
     * */
    static public function foreignSelect($query, $selects = null)
    {
        $products = new Product();
        return $products->withJoin(is_null($selects) ? $products->defaultSelects : $selects, $query);
    }

    /**
     * @param Relation|Product $query
     * @param array $selects
     * @return Relation
     * */
    private function _withJoin($query, $selects = array())
    {
        return $query->with([
            'type' => function ($query) {
                Types::select($query);
            }
        ])->select('id', 'typeid')->addSelect($selects);
    }

    /**
     * @param array $selects
     * @param Relation|Product
     * @return Relation
     * */
    public function withJoin($selects = array(), $query = null)
    {
        return $this->_withJoin(is_null($query) ? $this : $query, is_array($selects) ? $selects : func_get_args());
    }

    public function type()
    {
        return $this->hasOne(Types::class, 'id', 'typeid');
    }
}
