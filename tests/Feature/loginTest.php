<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class loginTest extends TestCase
{

    /** usage create the test with:
     * // Create a test in the Feature directory...
     *   php artisan make:test UserTest
     *
     * // Create a test in the Unit directory...
     *    php artisan make:test UserTest --unit
     *
     * and for run you can init the powershell and execute
     *  vendor/bin/phpunit
     */

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testPageOflogin()
    {
        $this->get('/login')
            ->assertStatus(200);
    }
}
