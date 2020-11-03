# revision-ten/rapidmail

## Installation

#### Install via composer

Run `composer req revision-ten/rapidmail`.

### Add the Bundle

Add the bundle to your AppKernel (Symfony 3.4.\*) or your Bundles.php (Symfony 4.\*).

Symfony 3.4.\* /app/AppKernel.php:
```PHP
new \RevisionTen\Rapidmail\RapidmailBundle(),
```

Symfony 4.\* /config/bundles.php:
```PHP
RevisionTen\Rapidmail\RapidmailBundle::class => ['all' => true],
```

### Configuration

Configure the bundle:

```YAML
rapidmail:
    api_username_hash: 'XXXXXXX'
    api_password_hash: 'XXXXXXX'
    campaigns:
        dailyNewsletterCampagin:
            list_id: '123456' # Id of your newsletter list.
```

### Usage

Use the RapidmailService to subscribe users.

Symfony 3.4.\* example:
```PHP
$rapidmailService = $this->container->get(RapidmailService::class);

$subscribed = $rapidmailService->subscribe('dailyNewsletterCampagin', 'visitor.email@domain.tld', 'My Website', [
    'FNAME' => 'John',
    'LNAME' => 'Doe',
]);
```

Or unsubscribe users:
```PHP
$rapidmailService = $this->container->get(RapidmailService::class);

$unsubscribed = $rapidmailService->unsubscribe('dailyNewsletterCampagin', 'visitor.email@domain.tld');
```
