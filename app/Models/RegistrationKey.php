<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class RegistrationKey extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'key_hash',
        'is_master',
        'is_used',
        'expires_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_master' => 'boolean',
        'is_used' => 'boolean',
        'expires_at' => 'datetime',
    ];

    /**
     * Checks whether a key is valid
     *
     * @param string $key The plain text key to validate
     * @return bool
     */
    public static function validateKey(string $key): bool
    {
        // Get the master key first
        $masterKey = self::where('is_master', true)->first();
        
        if ($masterKey && Hash::check($key, $masterKey->key_hash)) {
            return true;
        }
        
        // Look for non-master valid keys
        $validKey = self::where('is_used', false)
            ->where(function($query) {
                $query->whereNull('expires_at')
                      ->orWhere('expires_at', '>', Carbon::now());
            })
            ->get();
            
        foreach ($validKey as $registrationKey) {
            if (Hash::check($key, $registrationKey->key_hash)) {
                // Mark as used
                $registrationKey->is_used = true;
                $registrationKey->save();
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Creates a new master key with the given password
     *
     * @param string $key
     * @return RegistrationKey
     */
    public static function createMasterKey(string $key): self
    {
        // Delete any existing master keys
        self::where('is_master', true)->delete();
        
        return self::create([
            'key_hash' => Hash::make($key),
            'is_master' => true,
            'expires_at' => null,
        ]);
    }
    
    /**
     * Creates a one-time use registration key
     *
     * @param string|null $key If not provided, a random key will be generated
     * @param Carbon|null $expires_at When the key expires, null for never
     * @return array Contains the key and the model
     */
    public static function createOneTimeKey(?string $key = null, ?Carbon $expires_at = null): array
    {
        $plainKey = $key ?? \Illuminate\Support\Str::random(16);
        
        $registrationKey = self::create([
            'key_hash' => Hash::make($plainKey),
            'is_master' => false,
            'is_used' => false,
            'expires_at' => $expires_at,
        ]);
        
        return [
            'key' => $plainKey,
            'model' => $registrationKey,
        ];
    }
}
