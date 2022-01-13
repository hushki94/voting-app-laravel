<?php

namespace App\Models;

use App\Exceptions\DuplicateVoteException;
use App\Exceptions\VoteNotFoundException;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Idea extends Model
{
    use HasFactory,Sluggable;

    protected $guarded = [];





    public function user () {
        return $this->belongsTo(User::class);
    }

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    public function category () {
        return $this->belongsTo(Category::class);
    }

    public function status () {
        return $this->belongsTo(Status::class);
    }

    public function votes() {
        return $this->belongsToMany(User::class,'votes');
    }


    public function getCurrentStatus () {
        $allStatus = [
            'Opened' => 'bg-gray-200',
            'Considering' => 'bg-purple text-white',
            'In Progress' => 'bg-yellow text-white',
            'Implemented' => 'bg-green text-white',
            'Closed'=> 'bg-red text-white'
        ];

        return $allStatus[$this->status->name];
    }



    public function isVotedByUser(?User $user){
        if(!$user){
            return false;
        }
        return Vote::where('user_id',$user->id)
        ->where('idea_id' , $this->id)
        ->exists();
    }


    public function vote(User $user)
    {

        if($this->isVotedByUser($user)){
            throw new DuplicateVoteException;
        }
        Vote::factory()->create([
            'idea_id'=>$this->id,
            'user_id'=>$user->id
        ]);
    }


    public function removeVote(User $user)
    {
        $voteToDlete = Vote::where('idea_id',$this->id)
            ->where('user_id', $user->id);


            if($voteToDlete){
                $voteToDlete->delete();
            }else{
                throw new VoteNotFoundException;
            }

    }
}
