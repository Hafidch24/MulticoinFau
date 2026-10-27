#!/bin/bash
set -e

# ============ 1. إضافة حقل adblock في قاعدة البيانات ============
# (هذا الحقل يخزن حالة تفعيل مانع الإعلانات)
cat > database/migrations/2026_01_01_000012_add_adblock_to_users.php << 'PHPEOF'
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('users', function (Blueprint $t) {
            $t->boolean('adblock_disabled')->default(false)->after('status');
        });
    }
    public function down(): void {
        Schema::table('users', function (Blueprint $t) {
            $t->dropColumn('adblock_disabled');
        });
    }
};
PHPEOF

# ============ 2. تحديث FaucetController لتمرير البيانات ============
cat > app/Http/Controllers/FaucetController.php << 'PHPEOF'
<?php
namespace App\Http\Controllers;

use App\Models\{Currency, Wallet, Withdrawal};
use App\Services\{AntiFraudService, FaucetService};
use App\Services\Captcha\CaptchaService;
use Illuminate\Http\Request;

class FaucetController extends Controller
{
    public function __construct(
        private FaucetService $faucet,
        private AntiFraudService $fraud,
        private CaptchaService $captcha
    ) {}

    public function dashboard(Request $request)
    {
        $currencies = Currency::where('is_active', true)->get();
        $wallets    = $request->user()->wallets()->get()->keyBy('currency_id');
        $referralCount = $request->user()->referees()->count();
        
        return view('dashboard', compact('currencies', 'wallets', 'referralCount'));
    }

    public function claimPage(Request $request, $code)
    {
        $currency = Currency::where('code', strtoupper($code))->firstOrFail();
        $wallet = $request->user()->walletFor($currency->id);
        
        // حساب الوقت المتبقي (Timer)
        $lastClaim = \App\Models\FaucetClaim::where('user_id', $request->user()->id)
            ->where('currency_id', $currency->id)
            ->latest('claimed_at')->first();
        
        $wait = 0;
        if ($lastClaim) {
            $elapsed = now()->diffInSeconds($lastClaim->claimed_at);
            $wait = max(0, $currency->faucet_timer_seconds - $elapsed);
        }

        // حساب الحد اليومي
        $todayClaims = \App\Models\FaucetClaim::where('user_id', $request->user()->id)
            ->where('currency_id', $currency->id)
            ->whereDate('claimed_at', today())->count();
        $limit = $todayClaims >= $currency->faucet_daily_limit;

        return view('faucet', compact('currency', 'wallet', 'wait', 'limit', 'todayClaims'));
    }

    public function claim(Request $request)
    {
        $data = $request->validate(['currency_id' => ['required', 'exists:currencies,id']]);
        $currency = Currency::findOrFail($data['currency_id']);

        try {
            $claim = $this->faucet->claim($request->user(), $currency, $request->ip());
            $this->fraud->recordIp($request->user(), $request->ip());
            return back()->with('success', "Claimed {$claim->amount} {$currency->code}");
        } catch (\RuntimeException $e) {
            return back()->withErrors(['claim' => $e->getMessage()]);
        }
    }

    public function history(Request $request)
    {
        $withdrawals = $request->user()->withdrawals()->latest()->paginate(20);
        return view('history', compact('withdrawals'));
    }
}
PHPEOF

# ============ 3. تحديث المسارات ============
cat > routes/web.php << 'PHPEOF'
<?php
use App\Http\Controllers\{FaucetController, WithdrawalController, ProfileController};
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => view('welcome'))->name('home');

Route::middleware(['auth', 'active'])->group(function () {
    Route::get('/dashboard', [FaucetController::class, 'dashboard'])->name('dashboard');
    
    // صفحة العملة والادعاء
    Route::get('/faucet/currency/{code}', [FaucetController::class, 'claimPage'])->name('faucet.currency');
    Route::post('/faucet/claim', [FaucetController::class, 'claim'])->name('faucet.claim');
    
    // صفحة السجل
    Route::get('/history', [FaucetController::class, 'history'])->name('history');

    // السحوبات
    Route::get('/withdrawals', [WithdrawalController::class, 'index'])->name('withdrawals.index');
    Route::post('/withdrawals', [WithdrawalController::class, 'store'])->middleware('throttle:3,60')->name('withdrawals.store');

    // الملف الشخصي
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
require __DIR__.'/admin.php';
PHPEOF

# ============ 4. تصميم الصفحة الرئيسية (Dashboard) ============
cat > resources/views/dashboard.blade.php << 'PHPEOF'
@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="max-w-4xl mx-auto mt-8 px-4">
    <!-- الترحيب -->
    <div class="bg-gradient-to-r from-blue-500 to-blue-700 rounded-lg shadow-lg p-6 mb-6 text-white text-center">
        <h1 class="text-2xl font-bold mb-2">Welcome back, {{ auth()->user()->username }}!</h1>
        <p class="text-blue-100">Choose a cryptocurrency below to start claiming.</p>
    </div>

    <!-- الإعلان العلوي -->
    <div class="bg-white p-4 rounded-lg shadow-sm mb-6 text-center">
        {!! \App\Models\Setting::get('top_ad', '<span class="text-gray-400 text-sm">Top Ad Space</span>') !!}
    </div>

    <!-- العملات المتاحة -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
        @foreach($currencies as $currency)
            @php $wallet = $wallets->get($currency->id); @endphp
            <div class="bg-white rounded-lg shadow-md p-5 border border-gray-100 flex flex-col justify-between">
                <div class="flex justify-between items-center mb-3">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center">
                            <span class="font-bold text-xs">{{ substr($currency->code, 0, 2) }}</span>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-800">{{ $currency->name }}</h3>
                            <span class="text-xs text-gray-500">{{ $currency->code }}</span>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-xs text-gray-500">Balance</p>
                        <p class="font-mono font-bold text-green-600">{{ $wallet?->balance ?? '0.00000000' }}</p>
                    </div>
                </div>
                <a href="{{ route('faucet.currency', strtolower($currency->code)) }}" 
                   class="w-full bg-blue-500 hover:bg-blue-600 text-white text-center py-2 rounded font-medium transition">
                   Claim Now <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
        @endforeach
    </div>

    <!-- الإحالات -->
    <div class="bg-white p-5 rounded-lg shadow-sm mb-6 border border-gray-100">
        <h3 class="font-bold text-gray-700 mb-2"><i class="fas fa-users text-blue-500"></i> Referral Program</h3>
        <p class="text-sm text-gray-600 mb-3">Invite your friends and earn {{ \App\Models\Setting::get('referral_commission', 20) }}% of their earnings.</p>
        <div class="flex items-center bg-gray-50 rounded border p-2">
            <code class="flex-1 text-sm text-gray-700 break-all" id="refLink">https://{{ request()->getHost() }}/login?ref={{ auth()->user()->referral_code }}</code>
        </div>
        <p class="text-xs text-gray-500 mt-2">Total Referrals: <strong>{{ $referralCount }}</strong></p>
    </div>
</div>
@endsection
PHPEOF

# ============ 5. تصميم صفحة الفاوسيت (Faucet) ============
cat > resources/views/faucet.blade.php << 'PHPEOF'
@extends('layouts.app')

@section('title', 'Faucet ' . $currency->code)

@section('content')
<div class="max-w-4xl mx-auto mt-8 px-4">
    
    <!-- الإعلان العلوي -->
    <div class="bg-white p-4 rounded-lg shadow-sm mb-6 text-center">
        {!! \App\Models\Setting::get('top_ad', '') !!}
    </div>

    <!-- معلومات العملة -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="bg-blue-500 text-white p-4 flex justify-between items-center">
            <h2 class="text-xl font-bold"><i class="fas fa-coins"></i> Faucet {{ $currency->code }}</h2>
            <a href="{{ route('dashboard') }}" class="text-blue-100 hover:text-white text-sm"><i class="fas fa-arrow-left"></i> Back</a>
        </div>
        
        <div class="p-6 text-center border-b border-gray-100">
            <p class="text-gray-500 text-sm mb-1">Reward</p>
            <h1 class="text-3xl font-bold text-gray-800 mb-2">{{ $currency->faucet_reward }} {{ $currency->code }}</h1>
            
            <div class="flex justify-center gap-6 mt-4 text-sm">
                <div class="bg-gray-50 px-4 py-2 rounded border">
                    <span class="text-gray-500">Timer</span>
                    <strong class="block text-gray-800">{{ $currency->faucet_timer_seconds }}s</strong>
                </div>
                <div class="bg-gray-50 px-4 py-2 rounded border">
                    <span class="text-gray-500">Claims left</span>
                    <strong class="block text-gray-800">{{ $currency->faucet_daily_limit - $todayClaims }}/{{ $currency->faucet_daily_limit }}</strong>
                </div>
            </div>
        </div>

        <div class="p-6 bg-gray-50 text-center">
            @if($limit)
                <div class="bg-red-100 text-red-700 p-4 rounded mb-4">
                    <i class="fas fa-exclamation-triangle"></i> Daily limit reached. Come back tomorrow!
                </div>
            @elseif($wait > 0)
                <div class="bg-yellow-100 text-yellow-700 p-4 rounded mb-4">
                    <i class="fas fa-clock"></i> Please wait <strong id="timer">{{ $wait }}</strong> seconds...
                </div>
                <script>
                    let wait = {{ $wait }};
                    const timer = setInterval(() => {
                        wait--;
                        document.getElementById('timer').innerText = wait;
                        if(wait <= 0) {
                            clearInterval(timer);
                            location.reload();
                        }
                    }, 1000);
                </script>
            @else
                <form method="POST" action="{{ route('faucet.claim') }}" class="max-w-xs mx-auto">
                    @csrf
                    <input type="hidden" name="currency_id" value="{{ $currency->id }}">
                    
                    <!-- الكابتشا (سنضعها هنا لاحقاً) -->
                    <div class="bg-gray-200 rounded p-4 mb-4 text-gray-500 text-sm">
                        Captcha will load here (reCAPTCHA)
                    </div>

                    <button type="submit" class="w-full bg-green-500 hover:bg-green-600 text-white font-bold py-3 px-4 rounded transition shadow">
                        <i class="fas fa-check-circle"></i> Claim Now
                    </button>
                </form>
            @endif
        </div>
    </div>

    <!-- إعلان سفلي -->
    <div class="bg-white p-4 rounded-lg shadow-sm mt-6 text-center">
        {!! \App\Models\Setting::get('footer_ad', '') !!}
    </div>
</div>
@endsection
PHPEOF

# ============ 6. تحديث Layout الأساسي (للمستخدم) ============
cat > resources/views/layouts/app.blade.php << 'PHPEOF'
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Faucet') - {{ \App\Models\Setting::get('faucet_name', 'MultiCoin Faucet') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">

    <!-- Navbar -->
    <nav class="bg-white shadow">
        <div class="max-w-6xl mx-auto px-4 py-3 flex justify-between items-center">
            <a href="{{ route('dashboard') }}" class="font-bold text-xl text-blue-600">
                💰 {{ \App\Models\Setting::get('faucet_name', 'Faucet') }}
            </a>
            <div class="flex items-center gap-4 text-sm">
                @auth
                    <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-blue-600">Dashboard</a>
                    <a href="{{ route('withdrawals.index') }}" class="text-gray-600 hover:text-blue-600">Withdraw</a>
                    <a href="{{ route('history') }}" class="text-gray-600 hover:text-blue-600">History</a>
                    <a href="{{ route('profile.edit') }}" class="text-gray-600 hover:text-blue-600">Profile</a>
                    @if(auth()->user()->is_admin)
                        <a href="{{ route('admin.overview') }}" class="text-red-600 font-bold hover:text-red-800">Admin</a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}">@csrf
                        <button class="text-gray-500 hover:text-red-500">Logout</button>
                    </form>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-1">
        @if(session('success'))
            <div class="max-w-4xl mx-auto mt-4 px-4">
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-3 rounded shadow-sm text-sm">
                    {{ session('success') }}
                </div>
            </div>
        @endif
        @if($errors->any())
            <div class="max-w-4xl mx-auto mt-4 px-4">
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-3 rounded shadow-sm text-sm">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                    </ul>
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t mt-auto py-4 text-center text-sm text-gray-500">
        &copy; {{ date('Y') }} {{ \App\Models\Setting::get('faucet_name', 'Faucet') }}. All rights reserved.
    </footer>

    <!-- AdBlock Detection -->
    <script>
        // كود بسيط لكشف مانع الإعلانات (يمكن تطويره لاحقاً)
        setTimeout(function() {
            var ad = document.createElement('div');
            ad.className = 'adsbox';
            ad.style.height = '1px';
            document.body.appendChild(ad);
            setTimeout(function() {
                if (ad.offsetHeight === 0) {
                    Swal.fire({
                        icon: 'error',
                        title: 'AdBlocker Detected',
                        text: 'Please disable your ad blocker to use this service.',
                        allowOutsideClick: false
                    }).then(() => { window.location.reload(); });
                }
                ad.remove();
            }, 100);
        }, 500);
    </script>
</body>
</html>
PHPEOF

php artisan migrate --force
php artisan view:clear
php artisan route:clear
php artisan config:clear

echo "✅ تم بناء واجهة المستخدم الاحترافية بنجاح!"
