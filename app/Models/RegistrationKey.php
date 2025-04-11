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
        'school_id',
        'key_type',
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
     * Get the school this key is associated with
     */
    public function school()
    {
        return $this->belongsTo(School::class);
    }

    /**
     * Get the temporary key associated with this registration key
     */
    public function temporaryKey()
    {
        return $this->hasOne(TemporaryKey::class);
    }

    /**
     * Checks whether a key is valid
     *
     * @param string $key The plain text key to validate
     * @return array|bool Returns an array with key info if valid, false otherwise
     */
    public static function validateKey(string $key)
    {
        // Get the master key first
        $masterKey = self::where('is_master', true)->first();

        if ($masterKey && Hash::check($key, $masterKey->key_hash)) {
            return [
                'valid' => true,
                'is_master' => true,
                'school_id' => null,
                'key_type' => null
            ];
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
                return [
                    'valid' => true,
                    'is_master' => false,
                    'school_id' => $registrationKey->school_id,
                    'key_type' => $registrationKey->key_type
                ];
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
     * @param int|null $school_id The ID of the school this key is for
     * @param string $key_type The type of key (teacher or teacher_admin)
     * @return array Contains the key and the model
     */
    public static function createOneTimeKey(?string $key = null, ?Carbon $expires_at = null, ?int $school_id = null, string $key_type = 'teacher'): array
    {
        $plainKey = $key ?? \Illuminate\Support\Str::random(16);

        $registrationKey = self::create([
            'key_hash' => Hash::make($plainKey),
            'is_master' => false,
            'is_used' => false,
            'school_id' => $school_id,
            'key_type' => $key_type,
            'expires_at' => $expires_at,
        ]);

        // Store the plain key in the temporary_keys table
        // It will be displayed in the admin panel and will expire after 24 hours
        TemporaryKey::create([
            'registration_key_id' => $registrationKey->id,
            'plain_key' => $plainKey,
            'expires_at' => now()->addDay(),
        ]);

        return [
            'key' => $plainKey,
            'model' => $registrationKey,
        ];
    }
}
