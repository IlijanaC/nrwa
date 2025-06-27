<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $table = 'CUSTOMER';
    protected $primaryKey = 'CUST_ID';
    public $incrementing = true; // Pošto je integer i auto-increment
    protected $keyType = 'int';  // Tip primarnog ključa je integer

    public $timestamps = false; // Ako nemaš created_at i updated_at kolone

    protected $fillable = [
        'ADDRESS',
        'CITY',
        'CUST_TYPE_CD',
        'FED_ID',
        'POSTAL_CODE',
        'STATE',
    ];

     public function products()
    {
        return $this->belongsToMany(Product::class, 'customer_product', 'CUST_ID', 'PRODUCT_CD');
    }
    
}
