<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Antonrom\ModelChangesHistory\Traits\HasChangesHistory;

class Profile extends Model
{
    use HasFactory, HasChangesHistory;

    /**
     * Connect Profile to skills 
     */
    public function skills(){
        return $this->belongsToMany(Skill::class, 'profile_has_skills');
    }

    /**
     * Connect Profile to tags
     */
    public function tags(){
        return $this->belongsToMany(Tag::class, 'profile_has_tags');
    }

    /**
     * Added Scopes for various actions
     */
    protected static function boot()
    {
	 	parent::boot();

	    // Archive Record
	    static::updating(function ($row) {
	      //  archiveRecord('projects', $row->id);
        });

	    static::creating(function ($row) {
            $row->updated_by = \Auth::user()->id;
	    });

        static::deleting(function ($row) {
            $row->updated_by = \Auth::user()->id;
            $row->delete_reason = \Session::get('delete_reason');
            $row->save();
        });
  }
}
