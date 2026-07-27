<?php

arch('app')
    ->expect('App')
    ->toUseStrictTypes()
    ->not->toUse(['dd', 'dump', 'ray', 'var_dump', 'print_r', 'exit', 'die']);

// Namespace & Directory Structure

arch('Models')
    ->expect('App\Models')
    ->toExtend('Illuminate\Database\Eloquent\Model')
    ->toOnlyBeUsedIn([
        'App\Actions',
        'App\Concerns',
        'App\Data',
        'App\Enums',
        'App\Http\Controllers',
        'App\Http\Middleware',
        'App\Http\Requests',
        'App\Http\Responses',
        'App\Models',
        'App\Notifications',
        'App\Policies',
        'App\Providers',
        'App\Repositories',
        'App\Rules',
        'App\Services',
        'Database\Factories',
        'Database\Seeders',
    ]);

arch('User model')
    ->expect('App\Models\User')
    ->toExtend('Illuminate\Foundation\Auth\User')
    ->toImplement('Laravel\Fortify\Contracts\PasskeyUser');

arch('Membership model')
    ->expect('App\Models\Membership')
    ->toExtend('Illuminate\Database\Eloquent\Relations\Pivot');

arch('Enums')
    ->expect('App\Enums')
    ->toBeEnums()
    ->toUseStrictTypes()
    ->toOnlyBeUsedIn([
        'App\Actions',
        'App\Concerns',
        'App\Data',
        'App\Enums',
        'App\Http\Controllers',
        'App\Http\Middleware',
        'App\Http\Requests',
        'App\Http\Responses',
        'App\Models',
        'App\Notifications',
        'App\Policies',
        'App\Rules',
        'App\Providers',
        'Database\Factories',
    ]);

arch('TeamRole enum')
    ->expect('App\Enums\TeamRole')
    ->toHaveMethods(['label', 'permissions', 'hasPermission', 'level', 'isAtLeast', 'assignable']);

arch('TeamPermission enum')
    ->expect('App\Enums\TeamPermission')
    ->toBeEnums();

arch('Controllers')
    ->expect('App\Http\Controllers')
    ->toExtend('App\Http\Controllers\Controller');

arch('Controller base')
    ->expect('App\Http\Controllers\Controller')
    ->toOnlyBeUsedIn('App\Http\Controllers');

arch('Middleware')
    ->expect('App\Http\Middleware')
    ->toOnlyBeUsedIn([
        'App\Http\Controllers',
        'App\Providers',
        'routes',
    ]);

arch('Form Requests')
    ->expect('App\Http\Requests')
    ->toExtend('Illuminate\Foundation\Http\FormRequest');

arch('Responses')
    ->expect('App\Http\Responses')
    ->toOnlyBeUsedIn([
        'App\Http\Responses',
        'App\Providers',
    ]);

arch('LoginResponse contract')
    ->expect('App\Http\Responses\LoginResponse')
    ->toImplement('Laravel\Fortify\Contracts\LoginResponse');

arch('PasskeyLoginResponse contracts')
    ->expect('App\Http\Responses\PasskeyLoginResponse')
    ->toImplement('Laravel\Passkeys\Contracts\PasskeyLoginResponse');

arch('RegisterResponse contract')
    ->expect('App\Http\Responses\RegisterResponse')
    ->toImplement('Laravel\Fortify\Contracts\RegisterResponse');

arch('TwoFactorLoginResponse contract')
    ->expect('App\Http\Responses\TwoFactorLoginResponse')
    ->toImplement('Laravel\Fortify\Contracts\TwoFactorLoginResponse');

arch('VerifyEmailResponse contract')
    ->expect('App\Http\Responses\VerifyEmailResponse')
    ->toImplement('Laravel\Fortify\Contracts\VerifyEmailResponse');

arch('Policies')
    ->expect('App\Policies')
    ->toOnlyBeUsedIn([
        'App\Http\Controllers',
        'App\Providers',
    ]);

arch('Providers')
    ->expect('App\Providers')
    ->toExtend('Illuminate\Support\ServiceProvider')
    ->toOnlyBeUsedIn('bootstrap');

arch('Data objects')
    ->expect('App\Data')
    ->toBeReadonly()
    ->toOnlyBeUsedIn([
        'App\Concerns',
        'App\Http\Controllers',
        'App\Http\Responses',
    ]);

arch('Notifications')
    ->expect('App\Notifications')
    ->toExtend('Illuminate\Notifications\Notification')
    ->toOnlyBeUsedIn([
        'App\Models',
        'App\Http\Controllers',
    ]);

arch('Rules')
    ->expect('App\Rules')
    ->toImplement('Illuminate\Contracts\Validation\ValidationRule')
    ->toOnlyBeUsedIn([
        'App\Actions',
        'App\Http\Controllers',
        'App\Http\Requests',
        'App\Providers',
    ]);

arch('Actions')
    ->expect('App\Actions')
    ->toOnlyBeUsedIn([
        'App\Actions',
        'App\Http\Controllers',
        'App\Providers',
    ]);

arch('Concerns')
    ->expect('App\Concerns')
    ->toOnlyBeUsedIn([
        'App\Actions',
        'App\Concerns',
        'App\Http\Controllers',
        'App\Http\Requests',
        'App\Http\Responses',
        'App\Models',
    ]);

arch('Console Commands')
    ->expect('App\Console\Commands')
    ->toExtend('Illuminate\Console\Command');

// Naming Conventions

arch('Controller naming')
    ->expect('App\Http\Controllers')
    ->toHaveSuffix('Controller');

arch('Request naming')
    ->expect('App\Http\Requests')
    ->toHaveSuffix('Request');

arch('Response naming')
    ->expect('App\Http\Responses')
    ->classes()
    ->toHaveSuffix('Response');

arch('Rule naming')
    ->expect('App\Rules')
    ->classes()
    ->toHaveSuffix('Rule');

// Global layer rules

arch('no dangerous calls')
    ->expect('dd')
    ->not->toBeUsed();

arch('no env calls in app')
    ->expect('env')
    ->not->toBeUsedIn('App');

arch('no raw database calls in controllers')
    ->expect('DB::raw')
    ->not->toBeUsedIn('App\Http\Controllers');

arch('no factory calls outside tests')
    ->expect('::factory')
    ->not->toBeUsedIn('App');