<?php

namespace App\Http\Livewire;

use App\Models\Comment;
use App\Models\Idea;
use Livewire\Component;
use Livewire\WithPagination;

class IdeaComments extends Component
{

    public $idea;

    use WithPagination;

    protected $listeners = ['commentWasAdded','commentWasDeleted' , 'statusWasUpdated'];

    public function mount(Idea $idea)
    {
        $this->idea = $idea;
    }


    public function commentWasAdded()
    {
        $this->idea->refresh();
        $this->goToPage($this->idea->comments()->paginate()->lastPage());
    }

    public function commentWasDeleted()
    {
        $this->idea->refresh();
        $this->goToPage(1);
    }

    public function statusWasUpdated()
    {
        $this->idea->refresh();
        $this->goToPage($this->idea->comments()->paginate()->lastPage());
    }


    public function render()
    {
        return view('livewire.idea-comments',[
            // 'comments' => $this->idea->comments,
            'comments' => Comment::with(['user','status'])->where('idea_id', $this->idea->id)->paginate()->withQueryString(),
        ]);
    }
}
