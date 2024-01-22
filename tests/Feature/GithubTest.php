<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Mockery;
use App\Models\User;
use App\Models\Engineer;
use Laravel\Socialite\Facades\Socialite;


class GithubTest extends TestCase
{
    use RefreshDatabase;

    public function test_github_login_redirects()
    {
        $this->get('/github/login')->assertRedirect();
    }

    public function test_github_login_allows_engineers()
    {
        $email = fake()->safeEmail();
        Engineer::factory()->create(['email' => $email]);

        $this->mockSocialiteUser($email);

        $this->get('/github/callback'); 

        $user = User::where('email', $email)->first();
        $this->assertNotNull($user);
    }

    public function test_github_login_allows_previous_users()
    {
        $email = fake()->safeEmail();
        User::factory()->create(['email' => $email]);

        $this->mockSocialiteUser($email);

        $this->get('/github/callback'); 
        $this->assertNotNull(User::where('email', $email)->first());
    }

    public function test_github_login_denies_unknown_users()
    {
        $email = fake()->safeEmail();

        $this->mockSocialiteUser($email);

        $this->get('/github/callback'); 
        $this->assertNull(User::where('email', $email)->first());
    }

    protected function mockSocialiteUser($email)
    {
        $mockSocialite = Mockery::mock('Laravel\Socialite\Contracts\Factory');
        $this->app['Laravel\Socialite\Contracts\Factory'] = $mockSocialite;

        $abstractUser = Mockery::mock('Laravel\Socialite\Two\User');
        $abstractUser->id = rand();
        $abstractUser->name = fake()->name();
        $abstractUser->email = $email;
        $abstractUser->avatar = fake()->imageUrl(100, 100, 'animals', true);

        $provider = Mockery::mock('Laravel\Socialite\Contract\Provider');
        $provider->shouldReceive('user')->andReturn($abstractUser);

        $mockSocialite->shouldReceive('driver')->andReturn($provider);
    }
}
