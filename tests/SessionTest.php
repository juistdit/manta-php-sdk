<?php
namespace Manta\Tests;

use PHPUnit\Framework\TestCase;

use Manta\Sdk as MantaSdk;
use Manta\Session;

/**
 * Created by PhpStorm.
 * User: kay
 * Date: 17-02-17
 * Time: 09:02
 */
class SessionTest extends TestCase
{

    private $_config;
    private $_session;

    public function setUp() {
        $this->_config = (include __DIR__ . '/config.php');
        $sdk = new MantaSdk($this->_config);
        $this->_session = $sdk->login($this->_config['username'], $this->_config['password']);
    }

    public function testGetNonExistantCompany()
    {
        $session = $this->_session;

        try {
            $session->getCompany(1024 * 1024 * 1024);
            $this->fail("Retrieving a non-existant company should not work.");
        } catch (\Manta\Exceptions\NoAccessException $e) {
            $this->assertTrue(true);
        }
    }

    public function testGetNotAccessibleCompany() {
        $session = $this->_session;

        try {
            $session->getCompany($this->_config['company_without_access']);
            $this->fail("Retrieving a company that is not linked to the current user should not work.");
        } catch (\Manta\Exceptions\NoAccessException $e) {
            $this->assertTrue(true);
        }
    }

    public function testGetCompany(){
        $session = $this->_session;

        try {
            $company = $session->getCompany($this->_config['company_accessible']);
            $this->assertInstanceOf(\Manta\DataObjects\Objects\Company::class, $company);
            $this->assertEquals($company->shop_name, "Testcompany1");
        } catch (\Manta\Exceptions\NoAccessException $e) {
            $this->fail(sprintf("Failed logging in with username %s and password %s on api %s.",
                $this->_config['api_url'], $this->_config['username'], $this->_config['password']));
        }
    }

    public function testGetCompanies() {
        $session = $this->_session;
        $companies = $session->getCompanies();
        $count = 0;
        foreach($companies as $company){
            $count++;
            $this->assertInstanceOf(\Manta\DataObjects\Objects\Company::class, $company);
            $this->assertInternalType('string', $company->shop_name);
        }
        if($count === 0){
            $this->fail("Expected atleast one company to be returned for the current brand.");
        }
    }
}