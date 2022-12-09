<?php

namespace Tests\Unit\Models;

use App\Models\Organisation\User;
use Tests\TestCase;

class UserTest extends TestCase
{

    public function test_generate_uuid_in_creating()
    {
        $user = new User();

        $this->assertNull($user->uuid);
        $this->assertTrue($user->save());
        $this->assertNotNull($user->uuid);

        $uuid = $user->uuid;
        $user->save();
        $this->assertEquals($uuid, $user->uuid);
    }

    public function test_set_custom_uuid()
    {
        $uuid = 'f4e0332b-1e0a-463b-bebe-74bc9f7cb271';
        $user = new User(['uuid' => $uuid]);

        $this->assertTrue($user->save());
        $this->assertEquals($uuid, $user->uuid);
    }

}
