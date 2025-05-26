<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 * title="Department",
 * description="Department model",
 * @OA\Xml(
 * name="Department"
 * ),
 * @OA\Property(
 * property="DEPT_ID", 
 * title="ID",
 * description="ID of the department",
 * type="integer",
 * format="int64",
 * example=1
 * ),
 * @OA\Property(
 * property="NAME", 
 * title="Name",
 * description="Name of the department",
 * type="string",
 * example="IT Department"
 * )
 * )
 */
class Department extends Model
{
    protected $table = 'department';
    protected $primaryKey = 'DEPT_ID';

    protected $fillable = ['NAME'];

    public $timestamps = false;
}