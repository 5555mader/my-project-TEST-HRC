<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentFile extends Model
{
    use HasFactory;

    protected $table = 'documents_file'; // ชี้ไปยังตารางใหม่
    protected $fillable = ['document_id', 'file_name', 'file_size', 'file_extension'];

    public function document() {
        return $this->belongsTo(Document::class);
    }
}