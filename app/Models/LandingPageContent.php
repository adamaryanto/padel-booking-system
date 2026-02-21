<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LandingPageContent extends Model
{
    protected $fillable = [
        'hero_title',
        'hero_subtitle',
        'hero_image',
        'hero_cta_text',
        'hero_cta_link',
        'about_title',
        'about_subtitle',
        'about_description',
        'about_image',
        'contact_address',
        'contact_phone',
        'contact_email',
        'whatsapp_number',
    ];
}
