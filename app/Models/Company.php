<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use App\Traits\SearchableAttributes;

class Company extends Model
{
    use HasFactory;
    use SoftDeletes;
    use SearchableAttributes;

    public const STATUSES = [
        'ACTIVE' => 'active',
        'INACTIVE' => 'inactive',
    ];

    /**
     * key prefix
     */
    public const KEY_PREFIX = 'mini_prk';

    protected $hidden = [
        'secret_hash', 'deleted_at',
    ];

    protected $fillable=['user_id','name','api_key','secret_hash','status'];

    /**
    * The encrypted field or column
    *
    * @var string
    */
    protected $encrypted = 'api_key';
    /**
    * The blind index field or column
    *
    * @var string
    */
    protected $blindIndex = 'secret_hash';

    /**
     * return user relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user():\Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function setApiKeyAttribute(string $key):void
    {
        $this->attributes['api_key'] = $this->encrypt($key);
    }

    /**
     * Sets the value secret hash column
     *
     * @param string|null $hash
     *
     * @throws \ParagonIE\CipherSweet\Exception\BlindIndexNotFoundException
     * @throws \ParagonIE\CipherSweet\Exception\CryptoOperationException
     * @throws \SodiumException
     *
     * @return void
     */
    public function setSecretHashAttribute(?string $hash = null): void
    {
        $this->attributes['secret_hash'] = $this->blindIndexValue();
    }

    /**
     * Get the value for API key column
     *
     * @param string $encryptedKey
     *
     * @return mixed
     */
    public function getApiKeyAttribute(string $encryptedKey)
    {
        return $this->decrypt($encryptedKey);
    }

    /**
     * Add where clause for status equals active
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', self::STATUSES['ACTIVE']);
    }
    /**
     * Add where clause for status equals active
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeInactive(Builder $query): Builder
    {
        return $query->where('status', self::STATUSES['INACTIVE']);
    }

    /**
     * Find company by key
     *
     * @param string $Key
     *
     * @return static|null
     */
    public static function findActiveByKey(string $key): ?self
    {
        return static::query()
            ->active()
            ->whereBlindIndex($key)
            ->first();
    }

    /**
    * Find company by key
    *
    * @param string $Key
    *
    * @return static|null
    */
    public static function findInactiveByKey(string $Key): ?self
    {
        return static::query()
            ->inactive()
            ->whereBlindIndex($Key)
            ->first();
    }
    /**
    * Find comppanyy by key
    *
    * @param string $Key
    *
    * @return static|null
    */
    public static function findByKey(string $Key): ?self
    {
        return self::withTrashed()
            ->with('user')
            ->whereBlindIndex($Key)
            ->first();
    }

    /**
     * Generates the apiKey.
     * @throws \Exception
     *
     * @return array
     */
    public static function generateKey(): array
    {
        $Key = self::generateApiKey();

        return [
            'api_key' => self::KEY_PREFIX . '_' . $Key,
            'secret_hash' => null,
            'status' => self::STATUSES['ACTIVE'],
        ];
    }

    /**
     * Generate unique public and secret key pairs.
     * @throws \Exception
     *
     * @return string
     */
    private static function generateApiKey(): string
    {
        do {
            $Key = bin2hex(random_bytes(20));

            $secretHash = (new self())->getBlindIndexValueFor($Key);
            $hashExists = self::where('secret_hash', $secretHash)->withTrashed()->exists();
        } while ($hashExists);

        return $Key;
    }
}
