<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GamePlayer extends Model
{
    protected $fillable = ['gameId', 'userId', 'score', 'timesKnocked', 'roundsWon', 'roundsWonWithJack', 'status'];
}
