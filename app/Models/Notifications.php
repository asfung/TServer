<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Notifications extends Model
{

  use HasFactory, HasUuids;

  protected $fillable = [
    'id',
    'type',
    'notifiable_id',
    'notifiable_type',
    'data',
    'read_at'
  ];

  protected $casts = [
    'data' => 'array',
    'read_at' => 'datetime',
  ];
}
