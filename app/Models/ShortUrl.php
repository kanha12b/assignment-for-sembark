<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShortUrl extends Model
{
    use HasFactory;

        protected $fillable = [
        'original_url',
        'short_code',
        'company_id',
        'created_by'
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

        public function company()
    {
        return $this->belongsTo(Company::class);
    }

}
