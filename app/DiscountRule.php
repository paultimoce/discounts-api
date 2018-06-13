<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DiscountRule extends Model
{
    protected $table = 'discount_rules';

    protected $fillable = [
        'id',
        'discount_type_id',
        'category',
        'quantity',
        'threshold',
        'percentage',
        'operator'
    ];

    protected $hidden = ['discount_type_id'];
    public function type()
    {
        return $this->belongsTo(DiscountType::class, 'discount_type_id');
    }
}
