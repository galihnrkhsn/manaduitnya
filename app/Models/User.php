<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $table = 'users';
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function wallets() {
        return $this->hasMany(Wallet::class);
    }

    public function categories() {
        return $this->hasMany(Category::class);
    }

    public function transactions() {
        return $this->hasMany(Transaction::class);
    }

    public function budgets() {
        return $this->hasMany(Budget::class);
    }

    public function setupDefaults(): void
    {
        $this->wallets()->create(['name' => 'Tunai']);

        $categories = [
            ['Makan & Minum', Transaction::EXPENSE, '#B45309'],
            ['Transportasi',  Transaction::EXPENSE, '#0369A1'],
            ['Tagihan',       Transaction::EXPENSE, '#475569'],
            ['Belanja',       Transaction::EXPENSE, '#BE185D'],
            ['Hiburan',       Transaction::EXPENSE, '#65A30D'],
            ['Kesehatan',     Transaction::EXPENSE, '#15803D'],
            ['Biaya Admin',   Transaction::EXPENSE, '#64748B'],
            ['Lainnya',       Transaction::EXPENSE, '#94A3B8'],
            ['Gaji',          Transaction::INCOME,  '#0D9488'],
            ['Lainnya',       Transaction::INCOME,  '#14B8A6'],
        ];

        foreach ($categories as [$name, $type, $color]) {
            $this->categories()->create(compact('name', 'type', 'color'));
        }
    }

    protected function name(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => ucwords(strtolower($value ?? '')),
        );
    }
}
