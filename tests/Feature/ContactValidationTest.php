<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Contact;

class ContactValidationTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        // Create a user for authenticated tests
        $this->user = User::factory()->create();
    }

    /** @test */
    public function name_is_required_when_creating_a_contact()
    {
        $this->actingAs($this->user); // Authenticate the user
        $response = $this->post(route('contacts.store'), [
            'name' => '',
            'contact' => '123456789',
            'email' => 'test@example.com',
        ]);

        $response->assertSessionHasErrors('name');
    }

    /** @test */
    public function name_must_be_at_least_5_characters_when_creating_a_contact()
    {
        $this->actingAs($this->user);
        $response = $this->post(route('contacts.store'), [
            'name' => 'abcd', // Less than 5 characters
            'contact' => '123456789',
            'email' => 'test@example.com',
        ]);

        $response->assertSessionHasErrors('name');

        $response = $this->post(route('contacts.store'), [
            'name' => 'abcde', // Exactly 5 characters
            'contact' => $this->faker->unique()->numerify('#########'),
            'email' => $this->faker->unique()->safeEmail(),
        ]);

        $response->assertSessionDoesNotHaveErrors('name');
    }

    /** @test */
    public function contact_is_required_when_creating_a_contact()
    {
        $this->actingAs($this->user);
        $response = $this->post(route('contacts.store'), [
            'name' => 'Valid Name',
            'contact' => '',
            'email' => 'test@example.com',
        ]);

        $response->assertSessionHasErrors('contact');
    }

    /** @test */
    public function contact_must_be_9_digits_when_creating_a_contact()
    {
        $this->actingAs($this->user);

        // Too short
        $response = $this->post(route('contacts.store'), [
            'name' => 'Valid Name',
            'contact' => '12345678',
            'email' => 'test@example.com',
        ]);
        $response->assertSessionHasErrors('contact');

        // Too long
        $response = $this->post(route('contacts.store'), [
            'name' => 'Valid Name',
            'contact' => '1234567890',
            'email' => 'test@example.com',
        ]);
        $response->assertSessionHasErrors('contact');

        // Not digits
        $response = $this->post(route('contacts.store'), [
            'name' => 'Valid Name',
            'contact' => '123abc789',
            'email' => 'test@example.com',
        ]);
        $response->assertSessionHasErrors('contact');

        // Valid
        $response = $this->post(route('contacts.store'), [
            'name' => 'Valid Name',
            'contact' => '123456789',
            'email' => $this->faker->unique()->safeEmail(),
        ]);
        $response->assertSessionDoesNotHaveErrors('contact');
    }

    /** @test */
    public function contact_must_be_unique_when_creating_a_contact()
    {
        $this->actingAs($this->user);
        Contact::factory()->create(['contact' => '111111111']);

        $response = $this->post(route('contacts.store'), [
            'name' => 'Another Name',
            'contact' => '111111111',
            'email' => 'another@example.com',
        ]);

        $response->assertSessionHasErrors('contact');
    }

    /** @test */
    public function email_is_required_when_creating_a_contact()
    {
        $this->actingAs($this->user);
        $response = $this->post(route('contacts.store'), [
            'name' => 'Valid Name',
            'contact' => '123456789',
            'email' => '',
        ]);

        $response->assertSessionHasErrors('email');
    }

    /** @test */
    public function email_must_be_a_valid_email_address_when_creating_a_contact()
    {
        $this->actingAs($this->user);
        $response = $this->post(route('contacts.store'), [
            'name' => 'Valid Name',
            'contact' => '123456789',
            'email' => 'invalid-email',
        ]);

        $response->assertSessionHasErrors('email');
    }

    /** @test */
    public function email_must_be_unique_when_creating_a_contact()
    {
        $this->actingAs($this->user);
        Contact::factory()->create(['email' => 'unique@example.com']);

        $response = $this->post(route('contacts.store'), [
            'name' => 'Another Name',
            'contact' => '999999999',
            'email' => 'unique@example.com',
        ]);

        $response->assertSessionHasErrors('email');
    }

    /** @test */
    public function name_is_required_when_updating_a_contact()
    {
        $this->actingAs($this->user);
        $contact = Contact::factory()->create();

        $response = $this->put(route('contacts.update', $contact->id), [
            'name' => '',
            'contact' => $contact->contact,
            'email' => $contact->email,
        ]);

        $response->assertSessionHasErrors('name');
    }

    /** @test */
    public function contact_must_be_9_digits_when_updating_a_contact()
    {
        $this->actingAs($this->user);
        $contact = Contact::factory()->create();

        $response = $this->put(route('contacts.update', $contact->id), [
            'name' => $contact->name,
            'contact' => '12345678', // Too short
            'email' => $contact->email,
        ]);
        $response->assertSessionHasErrors('contact');
    }

    /** @test */
    public function contact_must_be_unique_except_for_the_current_contact_when_updating()
    {
        $this->actingAs($this->user);
        $existingContact = Contact::factory()->create(['contact' => '111111111']);
        $contactToUpdate = Contact::factory()->create(['contact' => '222222222']);

        // Cannot update to an existing contact number from another contact
        $response = $this->put(route('contacts.update', $contactToUpdate->id), [
            'name' => 'Updated Name',
            'contact' => $existingContact->contact,
            'email' => $contactToUpdate->email,
        ]);
        $response->assertSessionHasErrors('contact');

        // Can update to its own contact number
        $response = $this->put(route('contacts.update', $contactToUpdate->id), [
            'name' => 'Updated Name',
            'contact' => $contactToUpdate->contact,
            'email' => $contactToUpdate->email,
        ]);
        $response->assertSessionDoesNotHaveErrors('contact');
    }

    /** @test */
    public function email_must_be_unique_except_for_the_current_contact_when_updating()
    {
        $this->actingAs($this->user);
        $existingContact = Contact::factory()->create(['email' => 'existing@example.com']);
        $contactToUpdate = Contact::factory()->create(['email' => 'toupdate@example.com']);

        // Cannot update to an existing email from another contact
        $response = $this->put(route('contacts.update', $contactToUpdate->id), [
            'name' => 'Updated Name',
            'contact' => $contactToUpdate->contact,
            'email' => $existingContact->email,
        ]);
        $response->assertSessionHasErrors('email');

        // Can update to its own email
        $response = $this->put(route('contacts.update', $contactToUpdate->id), [
            'name' => 'Updated Name',
            'contact' => $contactToUpdate->contact,
            'email' => $contactToUpdate->email,
        ]);
        $response->assertSessionDoesNotHaveErrors('email');
    }

    /** @test */
    public function unauthenticated_user_cannot_create_contact()
    {
        $response = $this->post(route('contacts.store'), [
            'name' => 'Test',
            'contact' => '123456789',
            'email' => 'test@example.com',
        ]);
        $response->assertRedirect('/login'); // Should redirect to login
    }

    /** @test */
    public function unauthenticated_user_cannot_edit_contact()
    {
        $contact = Contact::factory()->create();
        $response = $this->put(route('contacts.update', $contact->id), [
            'name' => 'Updated Name',
            'contact' => $contact->contact,
            'email' => $contact->email,
        ]);
        $response->assertRedirect('/login');
    }

    /** @test */
    public function unauthenticated_user_cannot_delete_contact()
    {
        $contact = Contact::factory()->create();
        $response = $this->delete(route('contacts.destroy', $contact->id));
        $response->assertRedirect('/login');
    }

    /** @test */
    public function authenticated_user_can_access_create_contact_form()
    {
        $this->actingAs($this->user);
        $response = $this->get(route('contacts.create'));
        $response->assertStatus(200); // OK
    }

    /** @test */
    public function authenticated_user_can_access_edit_contact_form()
    {
        $this->actingAs($this->user);
        $contact = Contact::factory()->create();
        $response = $this->get(route('contacts.edit', $contact->id));
        $response->assertStatus(200);
    }

    /** @test */
    public function authenticated_user_can_delete_contact()
    {
        $this->actingAs($this->user);
        $contact = Contact::factory()->create();
        $this->assertDatabaseHas('contacts', ['id' => $contact->id, 'deleted_at' => null]);

        $response = $this->delete(route('contacts.destroy', $contact->id));

        $response->assertRedirect(route('contacts.index'));
        $this->assertSoftDeleted('contacts', ['id' => $contact->id]);
    }

    /** @test */
    public function public_user_can_view_contact_list()
    {
        Contact::factory()->count(3)->create();
        $response = $this->get(route('contacts.index'));
        $response->assertStatus(200);
        $response->assertViewHas('contacts');
    }

    /** @test */
    public function public_user_can_view_contact_details()
    {
        $contact = Contact::factory()->create();
        $response = $this->get(route('contacts.show', $contact->id));
        $response->assertStatus(200);
        $response->assertViewHas('contact');
        $response->assertSee($contact->name);
    }
}