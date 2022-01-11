<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Idea;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShowIdeasTest extends TestCase
{
    use RefreshDatabase;

    public function test_list_of_ideas_shows_on_main_page(){



        $categoryOne = Category::factory()->create([
            'name'=>'Category 1'
        ]);

        $categoryTwo = Category::factory()->create([
            'name'=>'Category 2'
        ]);
        $ideaOne = Idea::factory()->create([
            'title'=>'my first title',
            'category_id'=>$categoryOne->id,
            'description'=>'first description',


        ]);

        $ideaTwo = Idea::factory()->create([
            'title'=>'my second title',
            'category_id'=>$categoryTwo->id,
            'description'=>'second description',


        ]);
        $response = $this->get(route('idea.index'));

        $response->assertSuccessful();

        $response->assertSee($ideaOne->title);
        $response->assertSee($ideaOne->description);
        $response->assertSee($categoryOne->name);

        $response->assertSee($ideaTwo->title);
        $response->assertSee($ideaTwo->description);
        $response->assertSee($categoryTwo->name);

    }



    public function test_single_idea_shows_correctly_on_the_show_page(){

        $categoryOne = Category::factory()->create(['name' => 'Category 1']);

        $idea = Idea::factory()->create([
            'title'=>'my first title',
            'category_id'=>$categoryOne->id,
            'description'=>'first description',
        ]);


        $response = $this->get(route('idea.show' , $idea));

        $response->assertSuccessful();

        $response->assertSee($idea->title);
        $response->assertSee($categoryOne->name);

        $response->assertSee($idea->description);

    }


    public function test_ideas_pagination_works(){

        $categoryOne = Category::factory()->create(['name' => 'Category 1']);

        Idea::factory(11)->create([
            'category_id' => $categoryOne->id,
        ]);

        $ideaOne = Idea::find(1);
        $ideaOne->title = 'my first idea';
        $ideaOne->save();


        $idea11 = Idea::find(11);
        $idea11->title = 'my eleventh idea';
        $idea11->save();

        $response = $this->get('/');

        $response->assertSee($ideaOne);
        $response->assertDontSee($idea11);


        // $response = $this->get('/?page=2');

        // $response->assertSee($idea11);
        // $response->assertDontSee($ideaOne);

    }
     /** @test */
    public function same_idea_title_different_slugs(){

        $categoryOne = Category::factory()->create(['name' => 'Category 1']);
         $ideaOne = Idea::factory()->create([
             'title' => 'My First Idea',
             'category_id' => $categoryOne->id,
             'description' => 'Description for my first idea',
         ]);

         $ideaTwo = Idea::factory()->create([
             'title' => 'My First Idea',
             'category_id' => $categoryOne->id,
             'description' => 'Another Description for my first idea',
         ]);

         $response = $this->get(route('idea.show', $ideaOne));

         $response->assertSuccessful();
         $this->assertTrue(request()->path() === 'ideas/my-first-idea');

         $response = $this->get(route('idea.show', $ideaTwo));

         $response->assertSuccessful();
         $this->assertTrue(request()->path() === 'ideas/my-first-idea-1');
     }
}
