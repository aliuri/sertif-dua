<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class KeycloakController extends Controller
{
    // Mengarahkan user ke halaman login Keycloak
    public function redirect()
    {
        // Mengintip isi konfigurasi yang dibaca Laravel
        // dd(config('services.keycloak'));
        return Socialite::driver('keycloak')->redirect();
    }

    public function callback()
    {
        try {
            // Ambil data user dari Keycloak
            $keycloakUser = Socialite::driver('keycloak')->user();
            $email = $keycloakUser->getEmail();

            // Cari user di database lokal berdasarkan email
            $user = User::where('email', $email)->first();

            // Jika user ditemukan di database, maka login-kan
            if ($user) {
                // Opsional: Jika Anda ingin memperbarui nama user di database lokal dengan nama dari Keycloak
                // $user->update(['name' => $keycloakUser->getName()]);

                Auth::login($user);

                return redirect()->route('home');
            }

            // JIKA USER TIDAK DITEMUKAN: Tolak akses dan kembalikan ke halaman login
            return redirect('/login')->with('error', "Akses ditolak: Email ({$email}) belum terdaftar di sistem kami. Silakan hubungi Administrator.");
        } catch (\Exception $e) {
            // Log error untuk memudahkan perbaikan jika terjadi masalah sistem
            \Illuminate\Support\Facades\Log::error('SSO Keycloak Error: ' . $e->getMessage());

            return redirect('/login')->with('error', 'Gagal memproses login dari Keycloak.');
        }
    }

    // Proses Logout
    public function logout()
    {
        Auth::logout();

        $baseUrl = config('services.keycloak.base_url');
        $realm = config('services.keycloak.realm');

        // Menggunakan config agar mendukung config caching
        $keycloakLogoutUrl = $baseUrl . '/realms/' . $realm . '/protocol/openid-connect/logout?redirect_uri=' . urlencode(url('/'));

        return redirect($keycloakLogoutUrl);
    }
}
