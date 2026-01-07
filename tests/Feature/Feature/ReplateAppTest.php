<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\FoodPost;
use Carbon\Carbon;

class ReplateAppTest extends TestCase
{
    // Trait ini akan me-reset database setelah setiap pengujian,
    // sehingga test tidak mengganggu data asli Anda.
    use RefreshDatabase;

    /**
     * Test 1: Menguji apakah halaman login dapat diakses oleh pengunjung (tamu).
     */
    public function test_guest_can_view_login_page(): void
    {
        // 1. Lakukan GET request ke halaman login
        $response = $this->get('/login');

        // 2. Pastikan request berhasil (status 200 OK)
        $response->assertStatus(200);

        // 3. Pastikan halaman yang ditampilkan adalah view 'auth.login'
        $response->assertViewIs('auth.login');
    }

    /**
     * Test 2: Menguji apakah pengguna yang sudah login dapat melihat halaman feed.
     */
    public function test_authenticated_user_can_view_feed(): void
    {
        // 1. Buat satu pengguna palsu di database
        /** @var \App\Models\User $user */
        $user = User::factory()->create();

        // 2. Bertindak sebagai pengguna tersebut (login) dan buka halaman feed
        $response = $this->actingAs($user)->get('/feed');

        // 3. Pastikan request berhasil
        $response->assertStatus(200);

        // 4. Pastikan ada tulisan 'Jelajahi Makanan' di halaman
        $response->assertSee('Jelajahi Makanan');
    }

    /**
     * Test 3: Menguji apakah pengguna dapat membuat postingan makanan baru.
     */
    public function test_user_can_create_a_food_post(): void
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();

        $postData = [
            'title' => 'Nasi Goreng Spesial',
            'description' => 'Nasi goreng enak buatan rumah.',
            'price' => 15000,
            'stock' => 10,
            'location' => 'Malang',
            'expires_at' => Carbon::now()->addDay()->toDateTimeString(),
            'agreements' => 'on' // 'on' karena ini adalah nilai dari checkbox
        ];

        // Bertindak sebagai user, kirim data ke endpoint 'post.store'
        $response = $this->actingAs($user)->post(route('post.store'), $postData);

        // Pastikan pengguna diarahkan kembali ke halaman feed
        $response->assertRedirect(route('feed'));

        // Pastikan data postingan benar-benar tersimpan di database
        $this->assertDatabaseHas('food_posts', [
            'title' => 'Nasi Goreng Spesial',
            'price' => 15000
        ]);
    }

    /**
     * Test 4: Menguji validasi, pengguna tidak bisa memposting makanan dengan harga > 35000.
     */
    public function test_user_cannot_create_post_with_invalid_price(): void
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();

        $postData = [
            'title' => 'Makanan Mahal',
            'price' => 50000, // Harga tidak valid
            'stock' => 5,
            'location' => 'Jakarta',
            'expires_at' => Carbon::now()->addDay()->toDateTimeString(),
            'agreements' => 'on'
        ];

        // Kirim data tidak valid
        $response = $this->actingAs($user)->post(route('post.store'), $postData);

        // Pastikan ada error validasi untuk field 'price'
        $response->assertSessionHasErrors('price');
    }

    /**
     * Test 5: Menguji hak akses, hanya admin yang bisa mengakses dashboard admin.
     */
    public function test_only_admin_can_access_admin_dashboard(): void
    {
        // Skenario 1: Sebagai Admin
        /** @var \App\Models\User $adminUser */
        $adminUser = User::factory()->create(['is_admin' => true]);
        $this->actingAs($adminUser)->get(route('admin.dashboard'))->assertStatus(200);

        // Skenario 2: Sebagai Pengguna Biasa
        /** @var \App\Models\User $regularUser */
        $regularUser = User::factory()->create(['is_admin' => false]);
        // Pengguna biasa akan diarahkan (status 302) ke halaman feed
        $this->actingAs($regularUser)->get(route('admin.dashboard'))->assertRedirect(route('feed'));
    }
}
