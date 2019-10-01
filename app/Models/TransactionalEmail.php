<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionalEmail extends Model
{
    protected $fillable = ['subject','content', 'status'];

}
