<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\Invitation;
use App\Models\ShortUrl;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AssignmentTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_and_member_can_create_short_urls_but_super_admin_cannot(): void
    {
        $company = Company::create(['name' => 'Acme']);
        $admin = User::factory()->admin()->create(['company_id' => $company->id]);
        $member = User::factory()->member()->create(['company_id' => $company->id]);
        $superAdmin = User::factory()->superAdmin()->create();

        $this->actingAs($admin)->post('/short-urls', [
            'original_url' => 'https://example.com/admin',
        ])->assertRedirect('/dashboard');

        $this->actingAs($member)->post('/short-urls', [
            'original_url' => 'https://example.com/member',
        ])->assertRedirect('/dashboard');

        $this->actingAs($superAdmin)->post('/short-urls', [
            'original_url' => 'https://example.com/super-admin',
        ])->assertForbidden();

        $this->assertDatabaseCount('short_urls', 2);
    }

    public function test_url_visibility_is_scoped_by_role(): void
    {
        [$companyA, $companyB] = [
            Company::create(['name' => 'Company A']),
            Company::create(['name' => 'Company B']),
        ];

        $superAdmin = User::factory()->superAdmin()->create();
        $adminA = User::factory()->admin()->create(['company_id' => $companyA->id]);
        $memberA = User::factory()->member()->create(['company_id' => $companyA->id]);
        $otherMemberA = User::factory()->member()->create(['company_id' => $companyA->id]);
        $adminB = User::factory()->admin()->create(['company_id' => $companyB->id]);

        $adminUrl = ShortUrl::create([
            'company_id' => $companyA->id,
            'user_id' => $adminA->id,
            'original_url' => 'https://example.com/admin-a',
            'code' => 'adm111',
        ]);
        $memberUrl = ShortUrl::create([
            'company_id' => $companyA->id,
            'user_id' => $memberA->id,
            'original_url' => 'https://example.com/member-a',
            'code' => 'mem111',
        ]);
        $otherCompanyUrl = ShortUrl::create([
            'company_id' => $companyB->id,
            'user_id' => $adminB->id,
            'original_url' => 'https://example.com/admin-b',
            'code' => 'adm222',
        ]);

        $this->actingAs($superAdmin)->get('/dashboard')
            ->assertOk()
            ->assertSee($adminUrl->original_url)
            ->assertSee($memberUrl->original_url)
            ->assertSee($otherCompanyUrl->original_url);

        $this->actingAs($adminA)->get('/dashboard')
            ->assertOk()
            ->assertSee($adminUrl->original_url)
            ->assertSee($memberUrl->original_url)
            ->assertDontSee($otherCompanyUrl->original_url);

        $this->actingAs($memberA)->get('/dashboard')
            ->assertOk()
            ->assertSee($memberUrl->original_url)
            ->assertDontSee($adminUrl->original_url)
            ->assertDontSee($otherCompanyUrl->original_url);

        $this->actingAs($adminA)->get(route('short-urls.show', $otherCompanyUrl))->assertForbidden();
        $this->actingAs($otherMemberA)->get(route('short-urls.show', $memberUrl))->assertForbidden();
    }

    public function test_public_short_url_redirects_without_authentication(): void
    {
        $company = Company::create(['name' => 'Acme']);
        $user = User::factory()->admin()->create(['company_id' => $company->id]);
        $shortUrl = ShortUrl::create([
            'company_id' => $company->id,
            'user_id' => $user->id,
            'original_url' => 'https://example.com/very/long/url',
            'code' => 'abc123',
        ]);

        $this->get('/'.$shortUrl->code)
            ->assertRedirect('https://example.com/very/long/url');
    }

    public function test_invitation_permissions_are_enforced(): void
    {
        $company = Company::create(['name' => 'Acme']);
        $superAdmin = User::factory()->superAdmin()->create();
        $admin = User::factory()->admin()->create(['company_id' => $company->id]);
        $member = User::factory()->member()->create(['company_id' => $company->id]);

        $this->actingAs($superAdmin)->post('/invitations', [
            'company_name' => 'New Company',
            'name' => 'New Admin',
            'email' => 'new-admin@example.com',
            'role' => User::ROLE_ADMIN,
            'password' => 'password',
        ])->assertRedirect('/dashboard');

        $this->actingAs($superAdmin)->post('/invitations', [
            'company_name' => 'Bad Company',
            'name' => 'Bad Member',
            'email' => 'bad-member@example.com',
            'role' => User::ROLE_MEMBER,
            'password' => 'password',
        ])->assertSessionHasErrors('role');

        $this->actingAs($admin)->post('/invitations', [
            'name' => 'Company Member',
            'email' => 'company-member@example.com',
            'role' => User::ROLE_MEMBER,
            'password' => 'password',
        ])->assertRedirect('/dashboard');

        $this->actingAs($member)->post('/invitations', [
            'name' => 'Blocked User',
            'email' => 'blocked@example.com',
            'role' => User::ROLE_MEMBER,
            'password' => 'password',
        ])->assertForbidden();

        $this->assertDatabaseHas('users', [
            'email' => 'company-member@example.com',
            'company_id' => $company->id,
            'role' => User::ROLE_MEMBER,
        ]);

        $this->assertSame(2, Invitation::count());
    }
}
