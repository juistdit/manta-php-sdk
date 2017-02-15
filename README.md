# An SDK in PHP for Manta (In-development)

This package enables brands/suppliers to communicate with mantagifts using their API back-end.  Manta collects and distribute international brands. More information can found on <https://www.mantagifts.com>.

## Installing
This package can be installed using Composer. TODO.

## Usage

After installing Manta using composer, the first step is to create a Manta SDK object and creating a session using your credentials:

```php
$sdk = new Manta\Sdk;
$session = $sdk->login("brand@example.com", "123456789IsNotASafePassword");
```

### Retrieving companies

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
