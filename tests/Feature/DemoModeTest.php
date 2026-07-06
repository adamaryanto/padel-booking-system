<?php

use App\Models\User;
use App\Models\Faq;

test('admin CRUD is blocked when APP_DEMO_MODE is true', function () {
    // Set demo mode to true
    \Illuminate\Support\Env::getRepository()->set('APP_DEMO_MODE', 'true');

    $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
    $faq = Faq::create([
        'question' => 'What is Padel?',
        'answer' => 'A sport.',
        'order' => 1
    ]);

    // Try to update FAQ (PUT request)
    $response = $this->actingAs($admin)->put(route('admin.faqs.update', $faq), [
        'question' => 'Updated?',
        'answer' => 'Yes',
        'order' => 1
    ]);

    // Assert redirect back with error
    $response->assertStatus(302);
    $response->assertSessionHas('error', 'Aksi dinonaktifkan dalam mode Demo Portfolio.');

    // Assert FAQ was not changed in the database
    $faq->refresh();
    expect($faq->question)->toBe('What is Padel?');

    // Clean up env
    \Illuminate\Support\Env::getRepository()->set('APP_DEMO_MODE', 'false');
});

test('admin CRUD is allowed when APP_DEMO_MODE is false', function () {
    // Set demo mode to false
    \Illuminate\Support\Env::getRepository()->set('APP_DEMO_MODE', 'false');

    $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
    $faq = Faq::create([
        'question' => 'What is Padel?',
        'answer' => 'A sport.',
        'order' => 1
    ]);

    // Try to update FAQ (PUT request)
    $response = $this->actingAs($admin)->put(route('admin.faqs.update', $faq), [
        'question' => 'Updated?',
        'answer' => 'Yes',
        'order' => 1
    ]);

    // Assert successful redirect
    $response->assertStatus(302);
    $response->assertSessionHasNoErrors();

    // Assert FAQ was changed in the database
    $faq->refresh();
    expect($faq->question)->toBe('Updated?');
});

test('admin accessing landing page is redirected to dashboard', function () {
    $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
    
    // Visit homepage as admin
    $response = $this->actingAs($admin)->get('/');
    
    // Assert redirect to admin dashboard
    $response->assertRedirect(route('admin.dashboard'));
});
