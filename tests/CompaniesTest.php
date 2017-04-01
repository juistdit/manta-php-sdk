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
class CompaniesTest extends TestCase
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

    public function testGetCompanyOneDetails(){
        $session = $this->_session;

        try {
            $company = $session->getCompany($this->_config['company_accessible']);
            $this->assertInstanceOf(\Manta\DataObjects\Objects\Company::class, $company);
            $this->assertEquals($this->_config['company_accessible_shop_name'],$company->shop_name);
        } catch (\Manta\Exceptions\NoAccessException $e) {
            $this->fail(sprintf("Failed requesting company with company id %s with username %s on api %s.",
                $this->_config['company_accessible'], $this->_config['username'], $this->_config['api_url']));
        }
    }

    public function testGetCompanies() {
        $session = $this->_session;
        $companies = $session->getCompanies();
        $count = 0;
        $accessible_found = false;
        foreach($companies as $company){
            $count++;
            $this->assertInstanceOf(\Manta\DataObjects\Objects\Company::class, $company);
            $this->assertInternalType('string', $company->shop_name);
            if($company->id === $this->_config['company_accessible']) {
                $accessible_found = true;
            }
        }
        if($count === 0){
            $this->fail("Expected atleast one company to be returned for the current brand.");
        } else if(!$accessible_found) {
            $this->fail(sprintf("Expected company id %s to be in the returned companies.", $this->_config['company_accessible']));
        }
    }
}