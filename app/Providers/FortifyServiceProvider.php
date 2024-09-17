<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use App\Models\Kelas;
use App\Models\Mapel;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Fortify\Contracts\LoginResponse;
use Laravel\Fortify\Contracts\RegisterResponse;
use Laravel\Fortify\Fortify;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        
        $this->app->instance(
            LoginResponse::class,
            new class implements LoginResponse
            {
                public function toResponse($request)
                {
                    if (Auth::user()->hasRole('admin')) {
                        return $request->wantsJson()
                            ? response()->json(['two_factor' => false])
                            : redirect(RouteServiceProvider::HOME_ADMIN);
                    }

                    if (Auth::user()->hasRole('guru')) {
                        return $request->wantsJson()
                            ? response()->json(['two_factor' => false])
                            : redirect(RouteServiceProvider::HOME_GURU);
                    }
                    if (Auth::user()->hasRole('siswa')) {
                        return $request->wantsJson()
                            ? response()->json(['two_factor' => false])
                            : redirect(RouteServiceProvider::HOME_SISWA);
                    }
                }
            }
        );
        $this->app->instance(
            RegisterResponse::class,
            new class implements RegisterResponse
            {
                public function toResponse($request)
                {
                    if (Auth::user()->hasRole('admin')) {
                        return $request->wantsJson()
                            ? response()->json(['two_factor' => false])
                            : redirect(RouteServiceProvider::HOME_ADMIN);
                    }

                    if (Auth::user()->hasRole('guru')) {
                        return $request->wantsJson()
                            ? response()->json(['two_factor' => false])
                            : redirect(RouteServiceProvider::HOME_GURU);
                    }
                    if (Auth::user()->hasRole('siswa')) {
                        return $request->wantsJson()
                            ? response()->json(['two_factor' => false])
                            : redirect(RouteServiceProvider::HOME_SISWA);
                    }
                }
            }
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Fortify::loginView(function () {
            return view('auth.login');
        });

        // Fortify::registerView(function () {
        //     $kelas = Kelas::all();
        //     $mapels = Mapel::all();

        //     return view('auth.register',compact('kelas', 'mapels'));
        // });

        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::transliterate(Str::lower($request->input(Fortify::username())).'|'.$request->ip());

            return Limit::perMinute(5)->by($throttleKey);
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });
    }
}
