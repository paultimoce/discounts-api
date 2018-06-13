<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DiscountType extends Model
{
    protected $table = 'discount_types';

    protected $fillable = ['handle', 'description'];
}
