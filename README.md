# DigitalOcean Bundle for Symfony and API Platform

DunglasDigitalOceanBundle allows using [the DigitalOcean API](https://docs.digitalocean.com/reference/api/api-reference/)
from your [Symfony](https://symfony.com) and [API Platform](https://api-platform.com) projects.
It registers the [DigitalOcean PHP API Client](https://github.com/DigitalOceanPHP/Client) as a Symfony service.

If you aren't yet a DigitalOcean user, use [this affiliate link](https://m.do.co/c/5d8aabe3ab80) to get $100 in free credit!

If you want to deploy your Symfony apps to DigitalOcean, [checkout this tutorial](https://github.com/dunglas/symfony-docker/blob/main/docs/production.md).

## Example

```php
// src/Controller/DigitalOceanController.php
namespace App\Controller;

use DigitalOceanV2\Client;
use DigitalOceanV2\ResultPager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController
{
    #[Route("/droplets")]
    public function listDroplets(Client $doClient): Response
    {
        // get the first 100 droplets as an array
        $droplets = $doClient->droplet()->getAll();
        
        // or create a new result pager
        $pager = new ResultPager($doClient);

        // and get all droplets as an array
        $droplets = $pager->fetchAll($doClient->droplet(), 'getAll'); 

        // ...
    }
}
```

Of course, you can also inject the client service into your commands, messenger handlers and any other service.

[More examples](https://github.com/DigitalOceanPHP/Client#examples).

## Installation

Make sure Composer is installed globally, as explained in the
[installation chapter](https://getcomposer.org/doc/00-intro.md)
of the Composer documentation.

### Applications that use Symfony Flex

Open a command console, enter your project directory and execute:

```console
composer require dunglas/digital-ocean-bundle symfony/http-client nyholm/psr7 guzzlehttp/promises
```

The previous command installs the bundle, Symfony HttpClient and [the dependencies it needs](https://symfony.com.ua/doc/current/http_client.html#psr-18-and-psr-17)
to "provide" [`psr/http-client-implementation`](https://packagist.org/providers/psr/http-client-implementation) and [`psr/http-factory-implementation`](https://packagist.org/providers/psr/http-factory-implementation).

Alternatively, you can use any other implementation of these interfaces such as Guzzle.

### Applications that don't use Symfony Flex

#### Step 1: Download the Bundle

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

```console
$ composer require dunglas/digital-ocean-bundle symfony/http-client nyholm/psr7 guzzlehttp/promises
```

#### Step 2: Enable the Bundle

Then, enable the bundle by adding it to the list of registered bundles
in the `config/bundles.php` file of your project:

```php
// config/bundles.php

return [
    // ...
    \Dunglas\DigitalOceanBundle\DunglasDigitalOceanBundle::class => ['all' => true],
];
```

## Configuration

```yaml
dunglas_digital_ocean:
    connections:
        primary: # can be any name
            token: <your-access-token>
        secondary:
            token: <your-access-token>
    default_connection: primary # If not set, the first in the list will be used
```

This configuration automatically registers the following autowiring aliases:

```
DigitalOceanV2\Client $primaryClient
DigitalOceanV2\Client $secondaryClient
```

If you have only one client, you can use this shortcut:

```yaml
dunglas_digital_ocean: <your-access-token>
```

Use the `DigitalOceanV2\Client` type to inject the default DigitalOcean client.

## Credits

This bundle is brought to you by [Kévin Dunglas](https://dunglas.fr).
It is sponsored by [Les-Tilleuls.coop](https://les-tilleuls.coop).
