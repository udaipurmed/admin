<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Speciality extends Model
{
	protected $table = 'speciality';
	protected $fillable = [
        'name','icon', 'is_deleted', 'logo'
    ];
}
