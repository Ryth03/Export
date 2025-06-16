<?php

namespace App\Models\QAD\Export;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExportDoc extends Model
{
    use HasFactory;
    protected $table = 'export_docs';
    protected $guarded = ['id'];


    public function details()
    {
        return $this->hasMany(ExportDocDetail::class, 'sod_nbr', 'so_nbr');
    }
}
