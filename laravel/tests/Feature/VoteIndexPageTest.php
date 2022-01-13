<?php

namespace Tests\Feature;

use App\Http\Livewire\IdeaIndex;
use App\Http\Livewire\IdeasIndex;
use App\Models\Category;
use App\Models\Idea;
use App\Models\Status;
use App\Models\User;
use App\Models\Vote;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

class VoteIndexPageTest extends TestCase
{


    use RefreshDatabase;


    public function test_index_page_contains_idea_index_livewire_component(){

        $user = User::factory()->create();

        $categoryOne = Category::factory()->create(['name' => 'Category 1']);
        $categoryTwo = Category::factory()->create(['name' => 'Category 2']);
        $statusOpen = Status::factory()->create(['name' => 'Open', 'classes' => 'bg-gray-200']);
        $statusConsidering = Status::factory()->create(['name' => 'Considering', 'classes' => 'bg-purple text-white']);

        $idea = Idea::factory()->create([
            'user_id'=>$user->id,
            'title' => 'my first title',
            'category_id' => $categoryOne->id,
            'status_id' => $statusOpen->id,
            'description' => 'first description',
        ]);



        $this->get(route('idea.index'))
            ->assertSeeLivewire('idea-index');
    }


    public function test_ideas_index_livewire_component_correctly_receives_votes_count(){

        $user = User::factory()->create();
        $userB = User::factory()->create();

        $categoryOne = Category::factory()->create(['name' => 'Category 1']);

        $statusOpen = Status::factory()->create(['name' => 'Open', 'classes' => 'bg-gray-200']);


        $idea = Idea::factory()->create([
            'user_id'=>$user->id,
            'title' => 'my first title',
            'category_id' => $categoryOne->id,
            'status_id' => $statusOpen->id,
            'description' => 'first description',
        ]);

        Vote::factory()->create([
           'idea_id'=>$idea,
           'user_id'=>$user->id
        ]);

        Vote::factory()->create([
            'idea_id'=>$idea,
            'user_id'=>$userB->id
         ]);



         Livewire::test(IdeasIndex::class)
            ->assertViewHas('ideas',function($ideas){
                return $ideas->first()->votes_count == 2;
            });
    }



    public function test_votes_count_shows_correctly_on_index_page_livewire_component(){

        $user = User::factory()->create();


        $categoryOne = Category::factory()->create(['name' => 'Category 1']);
        $categoryTwo = Category::factory()->create(['name' => 'Category 2']);
        $statusOpen = Status::factory()->create(['name' => 'Open', 'classes' => 'bg-gray-200']);
        $statusConsidering = Status::factory()->create(['name' => 'Considering', 'classes' => 'bg-purple text-white']);

        $idea = Idea::factory()->create([
            'user_id'=>$user->id,
            'title' => 'my first title',
            'category_id' => $categoryOne->id,
            'status_id' => $statusOpen->id,
            'description' => 'first description',
        ]);

        Livewire::test(IdeaIndex::class,[
            'idea'=>$idea,
            'votesCount'=>5
        ])->assertSet('votesCount' , 5);
    }


    public function test_user_who_is_logged_in_shows_voted_if_idea_already_voted_for()
    {
        $user = User::factory()->create();

        $categoryOne = Category::factory()->create(['name' => 'Category 1']);

        $statusOpen = Status::factory()->create(['name' => 'Open', 'classes' => 'bg-gray-200' , 'id'=>1]);

        $idea = Idea::factory()->create([
            'user_id' => $user->id,
            'category_id' => $categoryOne->id,
            'status_id' => $statusOpen->id,
            'title' => 'My First Idea',
            'description' => 'Description for my first idea',
        ]);

        Vote::factory()->create([
            'idea_id' => $idea->id,
            'user_id' => $user->id,
        ]);

        $idea->votes_count = 1;
        $idea->voted_by_user = 1;

        Livewire::actingAs($user)
            ->test(IdeaIndex::class, [
                'idea' => $idea,
                'votesCount' => 5,
            ])
            ->assertSet('hasVoted', true)
            ->assertSee('Voted');
    }



    public function user_who_is_not_logged_in_is_redirected_to_login_page_when_trying_to_vote()
    {
        $user = User::factory()->create();

        $categoryOne = Category::factory()->create(['name' => 'Category 1']);

        $statusOpen = Status::factory()->create(['name' => 'Open', 'classes' => 'bg-gray-200']);

        $idea = Idea::factory()->create([
            'user_id' => $user->id,
            'category_id' => $categoryOne->id,
            'status_id' => $statusOpen->id,
            'title' => 'My First Idea',
            'description' => 'Description for my first idea',
        ]);

        Livewire::test(IdeaIndex::class, [
                'idea' => $idea,
                'votesCount' => 5,
            ])
            ->call('vote')
            ->assertRedirect(route('login'));
    }


    public function test_user_can_vote_for_idea()
    {
        $user = User::factory()->create();

        $categoryOne = Category::factory()->create(['name' => 'Category 1']);

        $statusOpen = Status::factory()->create(['name' => 'Open', 'classes' => 'bg-gray-200']);

        $idea = Idea::factory()->create([
            'user_id' => $user->id,
            'category_id' => $categoryOne->id,
            'status_id' => $statusOpen->id,
            'title' => 'My First Idea',
            'description' => 'Description for my first idea',
        ]);

        $this->assertFalse($idea->isVotedByUser($user));
        $idea->vote($user);
        $this->assertTrue($idea->isVotedByUser($user));
    }

    public function test_user_can_remove_vote_for_idea()
    {
        $user = User::factory()->create();

        $categoryOne = Category::factory()->create(['name' => 'Category 1']);

        $statusOpen = Status::factory()->create(['name' => 'Open', 'classes' => 'bg-gray-200']);

        $idea = Idea::factory()->create([
            'user_id' => $user->id,
            'category_id' => $categoryOne->id,
            'status_id' => $statusOpen->id,
            'title' => 'My First Idea',
            'description' => 'Description for my first idea',
        ]);

        Vote::factory()->create([
            'idea_id' => $idea->id,
            'user_id' => $user->id,
        ]);

        $this->assertTrue($idea->isVotedByUser($user));
        $idea->removeVote($user);
        $this->assertFalse($idea->isVotedByUser($user));
    }

}
