<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OpenApi\Annotations as OA;

/** 
 * @OA\Schema(
 * title="Product",
 * description="Product model",
 * @OA\Xml(
 * name="Product"
 * ),
 * @OA\Property(
 * property="PRODUCT_CD",
 * type="string",
 * title="Product Code",
 * description="Unique code for the product",
 * example="PROD001"
 * ),
 * @OA\Property(
 * property="NAME",
 * type="string",
 * title="Name",
 * description="Name of the product",
 * example="Current Account"
 * ),
 * @OA\Property(
 * property="DATE_OFFERED",
 * type="string",
 * format="date",
 * title="Date Offered",
 * description="Date when the product was offered",
 * example="2023-01-01",
 * nullable=true
 * ),
 * @OA\Property(
 * property="DATE_RETIRED",
 * type="string",
 * format="date",
 * title="Date Retired",
 * description="Date when the product was retired",
 * example="2025-12-31",
 * nullable=true
 * ),
 * @OA\Property(
 * property="PRODUCT_TYPE_CD",
 * type="string",
 * title="Product Type Code (Foreign Key)",
 * description="Foreign key to ProductType",
 * example="CHK"
 * ),
 * @OA\Property(
 * property="product_type",
 * ref="#/components/schemas/ProductType",
 * description="Related product type object (loaded via relationship)"
 * )
 * )
 */
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

    public function customers()
{
    return $this->belongsToMany(Customer::class, 'customer_product', 'PRODUCT_CD', 'CUST_ID');
}

}