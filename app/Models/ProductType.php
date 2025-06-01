<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OpenApi\Annotations as OA;

/** 
 * @OA\Schema(
 * title="ProductType",
 * description="Product Type model",
 * @OA\Xml(
 * name="ProductType"
 * ),
 * @OA\Property(
 * property="PRODUCT_TYPE_CD",
 * type="string",
 * title="Product Type Code",
 * description="Unique code for the product type",
 * example="ACC"
 * ),
 * @OA\Property(
 * property="NAME",
 * type="string",
 * title="Name",
 * description="Name of the product type",
 * example="Account"
 * )
 * )
 */
class ProductType extends Model
{
    use HasFactory;

    protected $table = 'product_type'; // Provjeri da li je ovo ispravan naziv tablice
    public $timestamps = false;
    protected $primaryKey = 'PRODUCT_TYPE_CD';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'PRODUCT_TYPE_CD',
        'NAME'
    ];

    public function products()
    {
        return $this->hasMany(Product::class, 'PRODUCT_TYPE_CD', 'PRODUCT_TYPE_CD');
    }
}