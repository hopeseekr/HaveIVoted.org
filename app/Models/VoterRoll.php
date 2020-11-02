<?php declare(strict_types=1);

namespace App\Models;

use Carbon\Carbon;
use PHPExperts\ConciseUuid\ConciseUuidModel;

/**
 * @property string $id
 * @property string $county
 * @property string $state
 * @property string $last_name
 * @property string $given_names
 * @property int    $voter_id
 * @property string $voting_method
 * @property string $precinct
 * @property string $recorded_on
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class VoterRoll extends ConciseUuidModel
{
    protected $table = 'voter_rolls';

    // Allow every column to be written to.
    protected $guarded = [];
}
