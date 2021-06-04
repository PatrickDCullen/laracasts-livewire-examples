<?php

namespace Tests\Feature;

use App\Http\Livewire\SearchDropdown;
use Tests\TestCase;
use Livewire\Livewire;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SearchDropdownTest extends TestCase
{
    /** @test */
    public function search_dropdown_shows_on_main_page()
    {
        $this->get('/')->assertSeeLivewire('search-dropdown');
    }

    /** @test */
    public function search_dropdown_searches_correctly_if_song_exists()
    {
        Livewire::test(SearchDropdown::class)
            ->assertDontSee('John Lennon')
            ->set('search', 'Imagine')
            ->assertSee('John Lennon');
    }

    /** @test */
    public function shows_message_if_no_song_exists()
    {
        Livewire::test(SearchDropdown::class)
            ->assertDontSee('John Lennon')
            ->set('search', 'asdfghjklbgiownerdlbsf')
            ->assertSee('No results found for');
    }
}
