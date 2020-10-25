<?php declare(strict_types=1);

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use PHPExperts\ConciseUuid\ConciseUuidModel;

/**
 * @property string $id
 * @property string $county_id
 * @property string $last_name
 * @property string $given_names
 * @property int    $voter_id
 * @property string $voting_method
 * @property int    $precinct
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property County $county
 */
class VoterRollTX extends ConciseUuidModel
{
    // Allow every column to be written to.
    protected $guarded = [];

    public function county(): BelongsTo
    {
        return $this->belongsTo(County::class);
    }
}
