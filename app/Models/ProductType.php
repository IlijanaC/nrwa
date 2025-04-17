<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

class ProductType extends Model
{
    use HasFactory;

    protected $table = 'product_type';
    public $timestamps = false;  // Ovdje koristiš naziv svoje tablice
    protected $primaryKey = 'PRODUCT_TYPE_CD';  // Primarni ključ je 'PRODUCT_TYPE_CD'
    public $incrementing = false;  // Budući da koristiš string kao primarni ključ

    protected $fillable = [
        'PRODUCT_TYPE_CD', 
        'NAME'
    ];

    public function products()
{
    return $this->hasMany(Product::class, 'PRODUCT_TYPE_CD', 'PRODUCT_TYPE_CD');
}

}
