<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AvatarLead extends Model
{
    protected $fillable = ['name', 'email', 'company', 'role', 'use_case', 'ip_address', 'source', 'partner_type'];
}
