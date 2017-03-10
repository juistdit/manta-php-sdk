<?php
declare(strict_types=1);

namespace Manta\Tests;

use PHPUnit\Framework\TestCase;
use Manta\Sdk as MantaSdk;
use Manta\Session;

/**
 * Created by PhpStorm.
 * User: kay
 * Date: 17-02-17
 * Time: 09:01
 */
class SdkTest extends TestCase
{
    private $_config;

    public function setUp() {
        $this->_config = (include __DIR__ . '/config.php');

    }
    public function testLoginCorrect() {
        $sdk = new MantaSdk($this->_config);
        //testing with valid password
        try {
            $this->assertInstanceOf(Session::class, $sdk->login($this->_config['username'], $this->_config['password']));
        } catch (\Manta\Exceptions\AuthorizationException $e){
            $this->fail(sprintf("Failed logging in with username %s and password %s on api %s.",
                $this->_config['username'], $this->_config['password'], $this->_config['api_url']));
        }
        //testing with valid password for the second time
        try {
            $this->assertInstanceOf(Session::class, $sdk->login($this->_config['username'], $this->_config['password']));
        } catch (\Manta\Exceptions\AuthorizationException $e){
            $this->fail(sprintf("Failed logging in two times using the same sdk object with username %s and password %s on api %s.",
                $this->_config['username'], $this->_config['password'], $this->_config['api_url']));
        }
    }

    public function testLoginInvalid(){
        $sdk = new MantaSdk($this->_config);
        //testing with an invalid password
        try {
            $sdk->login("Invalid Username", "Invalid Password");
            $this->fail("Logging in with incorrect credentials should fail.");
        } catch (\Manta\Exceptions\AuthorizationException $e){
            $this->assertTrue(true);
        }
    }
}