<?php

namespace Tests\Unit;

use App\Models\County;
use App\Models\State;
use Tests\TestCase;

class StateText extends TestCase
{
    public function testCanGetAListOfStates()
    {
        $list = State::all()->toArray();

        $this->assertNotEmpty($list);

        // Make sure it at least contains the states of CA and TX.
        $foundCA = $foundTX = false;
        foreach ($list as $state) {
            if ($state['code'] === 'CA') {
                $foundCA = true;
            } elseif ($state['code'] === 'TX') {
                $foundTX = true;
            }
        }

        self::assertTrue($foundCA, "Oh no! CA wasn't in the states list!");
        self::assertTrue($foundTX, "Oh no! TX wasn't in the states list!");
    }

    public function testCanRetrieveStatesAsACodeHashMap()
    {
        $states = State::listByCode();

        // Ensure that we have both CA and TX.
        self::assertArrayHasKey('CA', $states);
        self::assertArrayHasKey('TX', $states);

        self::assertEquals('California', $states['CA']);
        self::assertEquals('Texas', $states['TX']);
    }

    public function testCanLookUpStatesByPostalAbbreviation()
    {
        $california = State::find('CA');
        self::assertInstanceOf(State::class, $california);
        self::assertEquals('California', $california->name);

        $texas = State::find('TX');
        self::assertInstanceOf(State::class, $texas);
        self::assertEquals('Texas', $texas->name);
    }

    /** @testdox Can grab all of a state's counties */
    public function testCanGrabAllOfAStatesCounties()
    {
        $county = County::query()->create([
            'name'  => 'Testing',
            'state' => 'TX',
        ]);

        /** @var State $texas */
        $texas = State::find('TX');
        $counties = $texas->counties;

        $foundCounty = false;
        foreach ($counties as $c) {
            if ($c->name = $county->name && $c->state = $county->state) {
                $foundCounty = true;
                break;
            }
        }

        $this->assertTrue($foundCounty, 'Did not find the county!');

        $county->delete();
    }
}
