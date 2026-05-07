<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Support\Facades\Hash;

class AuthTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    public function test_is_password_hash(): void
    {
        //arrange
        $password = bcrypt('123456');
        $this->assertTrue(Hash::check('123456', $password));
        $this->assertFalse(Hash::check('wrong', $password));
    }
}
