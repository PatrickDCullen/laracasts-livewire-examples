<?php

namespace Tests\Feature;

use Tests\TestCase;
use Livewire\Livewire;
use App\Mail\ContactFormMailable;
use App\Http\Livewire\ContactForm;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ContactFormTest extends TestCase
{
    /** @test */
    public function main_page_contains_contact_form_livewire_component()
    {
        $this->get('/')
            ->assertSeeLivewire('contact-form');
    }

    /** @test */
    public function contact_form_sends_out_an_email()
    {
        Mail::fake();

        Livewire::test(ContactForm::class)
            ->set('name', 'Patrick')
            ->set('email', 'jon@doe.com')
            ->set('phone', '5555555')
            ->set('message', 'This is a message.')
            ->call('submitForm')
            ->assertSee('We received your message successfully and will get back to you shortly!');

        Mail::assertSent(function (ContactFormMailable $mail) {
            $mail->build();

            return $mail->hasTo('admin@admin.com') &&
                $mail->hasFrom('jon@doe.com') &&
                $mail->subject === 'Contact Form Submission';
        });
    }

    /** @test */
    public function contact_form_name_field_is_required()
    {
        Livewire::test(ContactForm::class)
            ->set('email', 'jon@doe.com')
            ->set('phone', '5555555')
            ->set('message', 'This is a message.')
            ->call('submitForm')
            ->assertHasErrors(['name' => 'required']);
    }

    /** @test */
    public function contact_form_message_field_should_have_at_least_five_characters()
    {
        Livewire::test(ContactForm::class)
            ->set('name', 'Patrick')
            ->set('email', 'jon@doe.com')
            ->set('phone', '5555555')
            ->set('message', 'This')
            ->call('submitForm')
            ->assertHasErrors(['message' => 'min']);
    }
}
