<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Submission extends AggregatableModel
{
    use HasFactory;

    protected $guarded = [];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function submissionVersions(){
        return $this->hasMany(SubmissionVersion::class);
    }

    public static function boot(){
        parent::boot();
        self::deleting(function($submission){
            $submission->submissionVersions()->each(function($subver){
                $subver->delete();
            });
        });
    }
}
