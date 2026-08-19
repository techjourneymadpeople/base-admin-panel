<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use App\Models\User;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Actions\RedirectIfTwoFactorAuthenticatable;
use Laravel\Fortify\Fortify;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);
        Fortify::redirectUserForTwoFactorAuthenticationUsing(RedirectIfTwoFactorAuthenticatable::class);

        // Custom Authentication with Status Validation
        Fortify::authenticateUsing(function (Request $request) {
            $user = User::where('email', $request->email)->first();

            if ($user && Hash::check($request->password, $user->password)) {
                if ($user->status === 'nonactive') {
                    throw ValidationException::withMessages([
                        Fortify::username() => ['Akun Anda sedang Non-Aktif. Silakan hubungi Administrator untuk mengaktifkan kembali akun Anda.'],
                    ]);
                }

                if ($user->status === 'suspended') {
                    throw ValidationException::withMessages([
                        Fortify::username() => ['Akun Anda ditangguhkan (Suspended) sementara karena pelanggaran kebijakan. Silakan hubungi pihak Administrator.'],
                    ]);
                }

                if ($user->status === 'banned') {
                    throw ValidationException::withMessages([
                        Fortify::username() => ['Akun Anda telah diblokir secara permanen (Banned) oleh Administrator.'],
                    ]);
                }

                return $user;
            }

            return null;
        });

        // Register Fortify Authentication Views
        Fortify::loginView(function () {
            return view('auth.login');
        });

        Fortify::registerView(function () {
            $config = \App\Models\WebConfiguration::current();
            if (!$config->registration_enabled) {
                return redirect()->route('login')->with('status', 'Pendaftaran akun baru saat ini sedang ditutup oleh Administrator.');
            }
            return view('auth.register');
        });

        Fortify::requestPasswordResetLinkView(function () {
            return view('auth.forgot-password');
        });

        Fortify::resetPasswordView(function (Request $request) {
            return view('auth.reset-password', ['request' => $request]);
        });

        Fortify::verifyEmailView(function () {
            return view('auth.verify-email');
        });

        Fortify::twoFactorChallengeView(function () {
            return view('auth.two-factor-challenge');
        });

        Fortify::confirmPasswordView(function () {
            return view('auth.confirm-password');
        });

        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::transliterate(Str::lower($request->input(Fortify::username())).'|'.$request->ip());

            return Limit::perMinute(5)->by($throttleKey);
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });

        RateLimiter::for('passkeys', function (Request $request) {
            $credentialId = $request->input('credential.id');

            return Limit::perMinute(10)->by(
                ($credentialId ?: $request->session()->getId()).'|'.$request->ip()
            );
        });
    }
}
