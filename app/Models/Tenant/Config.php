<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
    use HasFactory;
    protected $fillable = ['company_name', 'vat', 'contact', 'email','alert_email' ,'address', 'logotipo', 'sender_email', 'sender_name', 'sender_cc_email', 'sender_cc_name', 'sender_bcc_email', 'sender_bcc_name', 'signature'];
    protected $table = 'config';

}
