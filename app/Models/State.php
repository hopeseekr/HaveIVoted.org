<?php declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property string $code The state's postal code abbreviation (e.g., TX, CA, NY).
 * @property string $name
 */
class State extends Model
{
    public $incrementing = false;
    public $timestamps = false;

    protected $primaryKey = 'code';

    public static function listByCode(): array
    {
        // @see https://stackoverflow.com/a/42355330/430062
        return self::query()->get()
            ->pluck('name', 'code')
            ->toArray();
    }

    public function counties(): HasMany
    {
        return $this->hasMany(County::class, 'state', 'code');
    }
}
