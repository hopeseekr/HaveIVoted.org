<?php declare(strict_types=1);

namespace App\Models;

use Carbon\Carbon;
use PHPExperts\ConciseUuid\ConciseUuidModel;

/**
 * @property string $voter_roll_id
 * @property string $state
 * @property string $zipcode
 * @property int    $ballot_id
 * @property string $ballot_status
 * @property string $challenge_reason
 * @property Carbon $sent_at
 * @property Carbon $received_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class VoterMetadata extends ConciseUuidModel
{
    protected $table = 'voter_metadata';

    protected $primaryKey = 'voter_roll_id';

    // Allow every column to be written to.
    protected $guarded = [];

    protected $dates = [
        'sent_at',
        'received_at',
    ];
}
