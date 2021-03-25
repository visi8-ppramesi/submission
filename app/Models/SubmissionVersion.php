<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubmissionVersion extends Model
{
    use HasFactory;

    protected $guarded = [];

    // protected $casts = [
    //     'submission_items' => 'array'
    // ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function nextVersion(){
        return $this->hasOne(SubmissionVersion::class, 'previous_submission_version_id');
    }

    public function previousVersion(){
        return $this->belongsTo(SubmissionVersion::class, 'previous_submission_version_id');
    }

    public function forward(){
        return $this->nextVersion()->with('forward');
    }

    public function backward(){
        return $this->previousVersion()->with('backward');
    }

    public function getForwardVersions(){
        $forwards = $this->forward;
        $this->forward = $forwards;
        return $this;
        // return $this->with('forward');
    }

    public function getBackwardVersions(){
        $backwards = $this->backward;
        $this->backward = $backwards;
        return $this;
    }

    public function getThis(){
        return $this;
    }
}
