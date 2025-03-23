<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MemLog extends Model
{
    protected $table = 'dbo.t_memlog'; // Set the table name
    protected $primaryKey = 'LogID';  // Set the primary key
    public $timestamps = false;      // Disable Laravel's timestamps
    protected $fillable = ['LogID', 'mem_cd', 'Login_time', 'Logout_time', 'Location'];
}
