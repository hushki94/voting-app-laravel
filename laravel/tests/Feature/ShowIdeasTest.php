<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Idea;
use App\Models\Status;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShowIdeasTest extends TestCase
{
    use RefreshDatabase;

    public function test_single_idea_shows_on_the_show_page()
    {

        $categoryOne = Category::factory()->create(['name' => 'Category 1']);

        $statusOpen = Status::factory()->create(['name' => 'Open', 'classes' => 'bg-gray-200']);


        $idea1 = Idea::factory()->create([
            'title' => 'my first title',
            'category_id' => $categoryOne->id,
            'status_id' => $statusOpen->id,
            'description' => 'first description',
        ]);



        $response = $this->get(route('idea.show' , $idea1));

        $response->assertSuccessful();

        $response->assertSee($idea1->title);
        $response->assertSee($categoryOne->name);
        $response->assertSee('<div class="bg-gray-200 text-xxs font-bold uppercase leading-none rounded-full text-center w-28 h-7 py-2 px-4">Open</div>', false);
        $response->assertSee($idea1->description);


    }

    public function test_list_of_ideas_shows_on_main_page()
    {

        $categoryOne = Category::factory()->create(['name' => 'Category 1']);
        $categoryTwo = Category::factory()->create(['name' => 'Category 2']);
        $statusOpen = Status::factory()->create(['name' => 'Open', 'classes' => 'bg-gray-200']);
        $statusConsidering = Status::factory()->create(['name' => 'Considering', 'classes' => 'bg-purple text-white']);

        $idea1 = Idea::factory()->create([
            'title' => 'my first title',
            'category_id' => $categoryOne->id,
            'status_id' => $statusOpen->id,
            'description' => 'first description',
        ]);

        $idea2 = Idea::factory()->create([
            'title' => 'my first title',
            'category_id' => $categoryTwo->id,
            'status_id' => $statusConsidering->id,
            'description' => 'first description',
        ]);

        $response = $this->get(route('idea.index'));

        $response->assertSuccessful();

        $response->assertSee($idea1->title);
        $response->assertSee($categoryOne->name);
        $response->assertSee('<div class="bg-gray-200 text-xxs font-bold uppercase leading-none rounded-full text-center w-28 h-7 py-2 px-4">Open</div>', false);
        $response->assertSee($idea1->description);

        $response->assertSee($idea2->title);
        $response->assertSee($categoryTwo->name);
        $response->assertSee('<div class="bg-purple text-white text-xxs font-bold uppercase leading-none rounded-full text-center w-28 h-7 py-2 px-4">Considering</div>', false);
        $response->assertSee($idea2->description);

    }


    public function test_same_idea_title_different_slugs()
    {

        $categoryOne = Category::factory()->create(['name' => 'Category 1']);
        $statusOpen = Status::factory()->create(['name' => 'Open', 'classes' => 'bg-gray-200']);

        $ideaOne = Idea::factory()->create([
            'title' => 'My First Idea',
            'category_id' => $categoryOne->id,
            'status_id' => $statusOpen->id,
            'description' => 'Description for my first idea',
        ]);

        $ideaTwo = Idea::factory()->create([
            'title' => 'My First Idea',
            'category_id' => $categoryOne->id,
            'status_id' => $statusOpen->id,
            'description' => 'Another Description for my first idea',
        ]);

        $response = $this->get(route('idea.show', $ideaOne));

        $response->assertSuccessful();
        $this->assertTrue(request()->path() === 'ideas/my-first-idea');

        $response = $this->get(route('idea.show', $ideaTwo));

        $response->assertSuccessful();
        $this->assertTrue(request()->path() === 'ideas/my-first-idea-2');
    }
}