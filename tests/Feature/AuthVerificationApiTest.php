<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthVerificationApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_verify_email_via_hash_without_signed_expiration_check(): void
    {
        $user = User::factory()->unverified()->create([
            'email' => 'user@example.com',
        ]);

        $response = $this->getJson('/api/email/verify/' . $user->id . '/' . sha1($user->getEmailForVerification()));

        $response->assertStatus(200)
            ->assertJsonFragment([
                'title' => 'Email has been verified successfully.',
            ]);

        $this->assertNotNull($user->fresh()->email_verified_at);
    }
}
