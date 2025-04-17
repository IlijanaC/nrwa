<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'PRODUCT';

    protected $primaryKey = 'PRODUCT_CD';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'PRODUCT_CD',
        'DATE_OFFERED',
        'DATE_RETIRED',
        'NAME',
        'PRODUCT_TYPE_CD'
    ];

    public $timestamps = false;

    // Relacija prema PRODUCT_TYPE
    public function productType()
    {
        return $this->belongsTo(ProductType::class, 'PRODUCT_TYPE_CD', 'PRODUCT_TYPE_CD');
    }
}
