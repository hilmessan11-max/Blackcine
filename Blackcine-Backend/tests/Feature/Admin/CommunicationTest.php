<?php

namespace Tests\Feature\Admin;

use App\Models\EmailTemplate;
use App\Models\NewsletterCampaign;
use App\Models\NewsletterSubscriber;
use App\Models\PushNotification;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class CommunicationTest extends TestCase
{
    use RefreshDatabase;

    public function test_communication_dashboard_is_accessible(): void
    {
        $user = $this->createAdminUser();
        $this->seedCommunicationData($user);

        $response = $this->actingAs($user)->get(route('admin.notifications.templates.index'));

        $response->assertOk()
            ->assertSee('COMMUNICATION')
            ->assertSee("Modèles d'e-mails")
            ->assertSee('Push Notifications');
    }

    public function test_newsletter_dashboard_is_accessible(): void
    {
        $user = $this->createAdminUser();
        $this->seedCommunicationData($user);

        $response = $this->actingAs($user)->get(route('admin.newsletter.subscribers.index'));

        $response->assertOk()
            ->assertSee('Abonnés Newsletter')
            ->assertSee('Total');
    }

    public function test_subscriber_export_returns_csv_filtered_by_search(): void
    {
        $user = $this->createAdminUser();

        NewsletterSubscriber::create([
            'email' => 'alice@example.com',
            'first_name' => 'Alice',
            'last_name' => 'Dupont',
            'source' => 'landing',
            'is_active' => true,
        ]);

        NewsletterSubscriber::create([
            'email' => 'bob@example.com',
            'first_name' => 'Bob',
            'last_name' => 'Martin',
            'source' => 'event',
            'is_active' => false,
        ]);

        $response = $this->actingAs($user)->post(route('admin.newsletter.subscribers.export'));

        $response->assertOk();
        $response->assertHeader('content-type', 'text/csv; charset=UTF-8');

        $csv = $response->streamedContent();

        $this->assertStringContainsString('alice@example.com', $csv);
        $this->assertStringNotContainsString('bob@example.com', $csv);
    }

    private function seedCommunicationData(User $user): void
    {
        EmailTemplate::create([
            'key' => 'welcome',
            'name' => 'Welcome Email',
            'subject' => 'Bienvenue',
            'body' => 'Bienvenue sur BlackCine',
            'description' => 'Message de bienvenue',
            'variables' => ['first_name'],
            'is_active' => true,
        ]);

        PushNotification::create([
            'title' => 'Nouvelle sortie',
            'message' => 'Découvrez notre sélection du jour.',
            'target_audience' => 'all',
            'status' => 'sent',
            'sent_at' => now(),
            'success_count' => 120,
            'failure_count' => 3,
            'created_by' => $user->id,
        ]);

        NewsletterSubscriber::create([
            'email' => 'newsletter@example.com',
            'first_name' => 'Nina',
            'last_name' => 'Diallo',
            'source' => 'homepage',
            'is_active' => true,
        ]);

        NewsletterCampaign::create([
            'name' => 'Campagne BlackCine',
            'subject' => 'Notre sélection de la semaine',
            'content' => 'Contenu de campagne',
            'from_name' => 'BlackCine',
            'from_email' => 'hello@blackcine.test',
            'status' => 'sent',
            'sent_at' => now(),
            'sent_count' => 1,
            'opened_count' => 1,
            'clicked_count' => 0,
            'bounced_count' => 0,
            'unsubscribed_count' => 0,
            'created_by' => $user->id,
            'total_recipients' => 1,
        ]);
    }

    private function createAdminUser(): User
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $permission1 = Permission::firstOrCreate(['name' => 'manage notifications', 'guard_name' => 'web']);
        $permission2 = Permission::firstOrCreate(['name' => 'manage newsletter', 'guard_name' => 'web']);

        $role = Role::firstOrCreate([
            'name' => 'Admin',
            'guard_name' => 'web',
        ]);

        $role->givePermissionTo([$permission1, $permission2]);

        $user = User::factory()->create();
        $user->assignRole($role);

        return $user;
    }
}
