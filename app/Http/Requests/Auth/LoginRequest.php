<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * จุดที่ 1: Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            // เปลี่ยนจาก 'email' => ['required', 'string', 'email'] เป็น 'name'
            'name' => ['required', 'string'], 
            'password' => ['required', 'string'],
        ];
    }

    /**
     * จุดที่ 2: Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        // เปลี่ยน Auth::attempt ให้ใช้ 'name' แทน 'email'
        if (! Auth::attempt($this->only('name', 'password'), $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                // คืนค่า Error กลับไปที่ฟิลด์ 'name'
                'name' => trans('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            // เปลี่ยนคีย์แจ้งเตือนการล็อคระบบชั่วคราวให้เป็น 'name' ด้วย
            'name' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * จุดที่ 3: Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        // เปลี่ยนการล็อคจำนวนครั้งการเข้าสู่ระบบผิดพลาด ให้จำจาก 'name' แทน 'email'
        return Str::transliterate(Str::lower($this->input('name')).'|'.$this->ip());
    }
}