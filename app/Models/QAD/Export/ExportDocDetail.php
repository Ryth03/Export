<?php

namespace App\Models\QAD\Export;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExportDocDetail extends Model
{
    use HasFactory;
    protected $table = 'export_doc_details';
    protected $guarded = ['id'];

    public function exportDoc()
    {
        return $this->belongsTo(ExportDoc::class, 'sod_nbr', 'so_nbr');
    }
}
