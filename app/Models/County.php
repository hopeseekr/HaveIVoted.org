<?php declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use PHPExperts\ConciseUuid\ConciseUuidModel;

/**
 * @property string $id
 * @property string $name
 * @property State  $state
 */
class County extends ConciseUuidModel
{
    public $timestamps = false;

    // Allow every column to be written to.
    protected $guarded = [];

    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class, 'state', 'code');
    }
}
