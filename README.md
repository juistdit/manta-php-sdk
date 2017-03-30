# An SDK in PHP for Manta (In-development)

This package enables brands/suppliers to communicate with mantagifts using their API back-end.  Manta collects and distribute international brands. More information can found on <https://www.mantagifts.com>.

## A. Installing
Installing the SKD can be done 2 ways:

### A1: Composer

Install using the [packagist package](https://packagist.org/packages/juistdit/manta-php-sdk) 
via [composer](https://getcomposer.org/):

```
composer require juistdit/manta-php-sdk
```

### A2: Phar Archive
Install by [downloading](https://github.com/juistdit/manta-php-sdk/releases) the latest release and including it:
```php
require_once __DIR__ . "/manta-php-sdk.phar";
```

## B. Usage

After installing Manta using composer, the first step is to create a Manta SDK object and creating a session using your credentials:

```php
$sdk = new Manta\Sdk;
$session = $sdk->login("brand@example.com", "123456789IsNotASafePassword");
```

## C. Retrieving companies

To retrieve information about a company one can do:
```php
$company = $session->getCompany($companyId);
```
Where `$companyId` is an integer with the company id about which you want receive information. The `$company` variable will be of the type `Manta\DataObjects\Company`.

**Note:** You can only retrieve information about companies that have made orders to your brand.

To retrieve all companies where your company has access to one can do:
```php
$companies = $session->getCompanies();
```
The `$companies` variable will be an iterator returning `Manta\DataObjects\Company` objects. To retrieve the all company names, one can do:
```php
$companies = $session->getCompanies();
foreach($companies as $company) {
	echo ' - ', $company->company, PHP_EOL;
}
```

## D. Using phpunit to run tests

From the root directory of the SDK, you can run the phpunit tests (see tests directory).
 
Command:
```./vendor/bin/phpunit --configuration phpunit.xml```