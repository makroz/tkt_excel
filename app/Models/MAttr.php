<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MAttr extends Model
{
    use HasFactory;
    protected $fillable = ["name", "title", "subtitle", "style","note", "note_style", "required", "value", "styles", "position", "visible", "is_detail", "m_field_id", "opt"];
    protected $attributes = [
        'style' => '{}',
        'note_style' => '{}'
    ];
}
