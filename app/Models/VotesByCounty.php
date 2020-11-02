<?php declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $county
 * @property string $state
 * @property int    $votes
 */
class VotesByCounty extends Model
{
    protected $table = 'votes_by_county';

    // @FIXME: Needs to be a read-only view.
}
