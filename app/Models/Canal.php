<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Canal extends Model
{
    use HasFactory;

    protected $fillable = ['nome']; // Campos que podem ser preenchidos em massa

    public function messages()
    {
        return $this->hasMany(Message::class); // Relacionamento com a tabela messages
    }
}

