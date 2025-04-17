<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $table = 'department';  // Definirajte naziv tablice ako nije uobičajeni oblik
    protected $primaryKey = 'DEPT_ID';
    protected $fillable = ['NAME'];
    public $timestamps = false;  // Ovdje onemogućavamo automatske timestamps ako ne postoje
}
