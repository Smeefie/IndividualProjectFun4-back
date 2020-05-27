<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoundPlayer extends Model
{
    protected $fillable = ['roundId', 'userId', 'score', 'withJack', 'timesKnocked', 'roundStatus', 'status'];
}
