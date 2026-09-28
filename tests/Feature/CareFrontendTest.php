<?php

use App\Models\Conversation;
use App\Models\StressModule;
use App\Models\User;
use App\Services\GroqService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->withoutVite();
    $this->conversation = Conversation::create([
        'token' => Str::random(40), 'alias' => 'Brave River 123', 'status' => 'pending',
        'risk_level' => 'low', 'is_human_request' => false,
    ]);
    $this->withSession(['chikomo_token' => $this->conversation->token]);
});

test('all public sections render Blade and share the anonymous identity', function (string $route, string $view) {
    $this->get(route($route))->assertOk()->assertViewIs($view)
        ->assertSee('Brave River 123')->assertSee(route('care.modules'))
        ->assertDontSee('id="root"', false);
    expect(Conversation::count())->toBe(1);
})->with([
    ['care.chat', 'care.chat'], ['care.counselor', 'care.counselor'],
    ['care.modules', 'care.modules'], ['care.stories', 'care.stories'],
]);

test('new sessions do not enter the human counselor queue', function () {
    $this->get(route('care.counselor'))->assertSee('Establish Connection Request')->assertDontSee('Placing Request in Active Queue');
    $this->getJson(route('care.counselor.sync'))->assertJsonPath('can_send', false);
});

test('AI messages are saved and returned on the Blade page', function () {
    $this->mock(GroqService::class)->shouldReceive('getResponse')->once()->andReturn('Let us talk about it.');
    $this->post(route('care.chat.send'), ['message' => 'I feel stressed', 'token' => 'untrusted'])
        ->assertRedirect(route('care.chat'));
    $this->assertDatabaseHas('messages', ['conversation_id' => $this->conversation->id, 'content' => 'I feel stressed']);
    $this->get(route('care.chat'))->assertSee('Let us talk about it.');
});

test('invalid messages preserve input and expose validation errors', function () {
    $this->from(route('care.chat'))->post(route('care.chat.send'), ['message' => str_repeat('x', 2001)])
        ->assertRedirect(route('care.chat'))->assertSessionHasErrors('message');
    $this->assertDatabaseCount('messages', 0);
});

test('counselor requests join the queue only once', function () {
    $this->post(route('care.counselor.request'), ['risk_level' => 'medium'])->assertRedirect(route('care.counselor'));
    expect($this->conversation->fresh()->is_human_request)->toBeTrue();
    $this->conversation->update(['status' => 'active', 'counselor_id' => User::factory()->create()->id]);
    $this->post(route('care.counselor.request'), ['risk_level' => 'low']);
    expect($this->conversation->fresh()->status)->toBe('active');
});

test('live counselor messages never invoke AI and closed sessions reject sends', function () {
    $this->mock(GroqService::class)->shouldNotReceive('getResponse');
    $this->conversation->update(['is_human_request' => true, 'status' => 'active', 'counselor_id' => User::factory()->create()->id]);
    $this->post(route('care.counselor.send'), ['message' => 'Hello counselor'])->assertRedirect(route('care.counselor'));
    $this->assertDatabaseCount('messages', 1);
    $this->getJson(route('care.counselor.sync'))->assertJsonPath('can_send', true)->assertSee('Hello counselor');
    $this->conversation->update(['status' => 'completed']);
    $this->post(route('care.counselor.send'), ['message' => 'Too late'])->assertStatus(409);
    $this->getJson(route('care.counselor.sync'))->assertJsonPath('can_send', false);
    $this->assertDatabaseCount('messages', 1);
});

test('peer stories remain moderated and user content is escaped', function () {
    $this->post(route('care.stories.post'), ['title' => '<script>alert(1)</script>', 'content' => 'My experience'])
        ->assertRedirect(route('care.stories'))->assertSessionHas('success');
    $this->assertDatabaseHas('peer_stories', ['author_alias' => $this->conversation->alias, 'is_approved' => 0]);
    $this->get(route('care.stories'))->assertDontSee('My experience');
    DB::table('peer_stories')->update(['is_approved' => 1]);
    $this->get(route('care.stories'))->assertSee('My experience')->assertSee('&lt;script&gt;', false)->assertDontSee('<script>alert', false);
});

test('module feedback is persisted and escaped', function () {
    $module = StressModule::create(['title' => 'Breathing', 'file_path' => 'uploads/stress_modules/test.pdf']);
    $this->post(route('care.modules.comment', $module), ['comment' => '<img src=x onerror=alert(1)>'])
        ->assertRedirect(route('care.modules'));
    $this->assertDatabaseHas('stress_module_comments', ['stress_module_id' => $module->id, 'author_alias' => $this->conversation->alias]);
    $this->get(route('care.modules'))->assertSee('Feedback (1)')->assertSee('&lt;img', false)->assertDontSee('<img src=x', false);
});

test('resource downloads count successful files and reject paths outside the upload directory', function () {
    $relativePath = 'uploads/stress_modules/test-'.Str::uuid().'.pdf';
    file_put_contents(public_path($relativePath), '%PDF-1.4 test');
    $module = StressModule::create(['title' => 'Guide', 'file_path' => $relativePath]);
    try {
        $this->post(route('care.modules.download', $module))->assertDownload(basename($relativePath));
        expect($module->fresh()->download_count)->toBe(1);
        $module->update(['file_path' => '../composer.json']);
        $this->post(route('care.modules.download', $module))->assertNotFound();
        expect($module->fresh()->download_count)->toBe(1);
    } finally {
        unlink(public_path($relativePath));
    }
});

test('conversation selection refuses another anonymous identity', function () {
    $other = Conversation::create(['token' => Str::random(40), 'alias' => 'Other Guest', 'status' => 'pending']);
    $this->post(route('care.conversations.select', $other))->assertNotFound();
    $this->post(route('care.conversations.select', $this->conversation))->assertRedirect(route('care.chat'));
});

test('legacy React identities can be restored with a valid token', function () {
    $old = Conversation::create(['token' => Str::random(40), 'alias' => 'Returning Guest', 'status' => 'pending']);
    $this->postJson(route('care.session.restore'), ['token' => 'invalid'])->assertNotFound();
    $this->postJson(route('care.session.restore'), ['token' => $old->token])->assertOk()->assertSessionHas('chikomo_token', $old->token);
    $this->get(route('care.chat'))->assertSee('Returning Guest');
});

test('frontend and compatibility endpoints use web middleware', function () {
    foreach (['care.chat', 'care.chat.send', 'care.counselor.send', 'care.stories.post', 'api.conversations.create'] as $name) {
        expect(Route::getRoutes()->getByName($name)->gatherMiddleware())->toContain('web');
    }
    expect(file_exists(base_path('routes/api.php')))->toBeFalse();
    $this->getJson('/api/stress-modules')->assertOk();
});
