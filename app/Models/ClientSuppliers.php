<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientSuppliers extends Model
{
    use HasFactory;

    protected $table = 'client_suppliers';

    protected $fillable = ['client_id', 'supplier_id'];

    public function client(){
        return $this->belongsTo(User::class, 'client_id', 'id');
    }

    public function supplier(){
        return $this->belongsTo(User::class, 'supplier_id', 'id');
    }
}
