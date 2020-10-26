<?php declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\VoterRoll;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VoterController extends Controller
{
    // @FIXME: Figure out how to require Laravel's CSRF.
    public function search(string $state, Request $request)
    {
        $this->validate($request, [
            'lastName'   => ['required'],
            'givenNames' => ['required'],
        ]);

        if ($state !== 'TX') {
            return new JsonResponse([
                'error' => "Searching voting rolls for $state is currently not implemented.",
            ], JsonResponse::HTTP_BAD_REQUEST);
        }

        $lastName = strtoupper($request->input('lastName'));
        $givenNames = strtoupper($request->input('givenNames'));

        $voters = VoterRoll::query()
            ->select(['last_name', 'given_names', 'c.name AS county', 'voting_method', 'precinct', 'recorded_on'])
            ->join('counties AS c', 'c.id', '=', 'voter_rolls.county_id')
            ->where('last_name', 'LIKE', "%$lastName")
            ->where('given_names', 'LIKE', "$givenNames%")
            ->orderBy('last_name')
            ->orderBy('given_names')
            ->orderBy('recorded_on')
        ->paginate(25);

        return new JsonResponse($voters);
    }
}
