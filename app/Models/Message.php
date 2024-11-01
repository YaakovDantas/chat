<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = ['canal_id', 'nome', 'texto']; // Campos que podem ser preenchidos em massa

    public function canal()
    {
        return $this->belongsTo(Canal::class); // Relacionamento com a tabela canals
    }
}
