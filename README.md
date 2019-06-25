# Sylius Callout Plugin

[![Latest Version][ico-version]][link-packagist]
[![Latest Unstable Version][ico-unstable-version]][link-packagist]
[![Software License][ico-license]](LICENSE)
[![Build Status][ico-travis]][link-travis]
[![Quality Score][ico-code-quality]][link-code-quality]

The callout plugin for [Sylius](https://sylius.com/) allows you to configure nice badges for different set of products
based on specific rules. It provides a common set of configuration by default and is very flexible when it comes to adding new ones.

## Installation

### Step 1: Download the plugin

Open a command console, enter your project directory and execute the following command to download the latest stable version of this plugin:

```bash
$ composer require setono/sylius-callout-plugin
```

This command requires you to have Composer installed globally, as explained in the [installation chapter](https://getcomposer.org/doc/00-intro.md) of the Composer documentation.

### Step 2: Enable the plugin

Then, enable the plugin by adding it to the list of registered plugins/bundles
in `config/bundles.php` file of your project *before* (!) `SyliusGridBundle`:

```php
<?php
$bundles = [
    Setono\SyliusCalloutPlugin\SetonoSyliusCalloutPlugin::class => ['all' => true],
    Sylius\Bundle\GridBundle\SyliusGridBundle::class => ['all' => true],
];
```

### Step 3: Configure plugin
```yaml
# config/packages/_sylius.yaml

imports:
    - { resource: "@SetonoSyliusCalloutPlugin/Resources/config/app/config.yaml" }
```

### Step 4: Import routing

```yaml
# config/routes/routes.yaml

setono_product_callout:
    resource: "@SetonoSyliusCalloutPlugin/Resources/config/routing.yaml"
```

### Step 5: Customize models and repositories
5. Customize your product model. Read more about Sylius models customization [here](https://docs.sylius.com/en/latest/customization/model.html).
- add a `Setono\SyliusCalloutPlugin\Model\CalloutsAwareTrait` trait to your `App\Entity\Product` class (check our [this path](tests/Application/src) for a reference),
- add callouts relation to your `Product.orm.xml` like [here](tests/Application/src/Resources/config/doctrine),
- if you haven't done so already, configure the `sylius_product` resource to point to your `App\Entity\Product` like we 
did in an example [here](tests/Application/src/Resources/config/resources.yml).

Implement `ProductRepositoryInterface`.

**Note:** We are using `.orm.xml` file format for entities configuration. You can use whatever format you wish. For more details
read the official [Symfony Doctrine configuration reference](https://symfony.com/doc/current/reference/configuration/doctrine.html) or
check out our configuration [here](tests/Application/config/packages/doctrine.yaml).

### Step 6: Add callouts to your product templates 
Add callouts to your product box template. By default, you should use `templates/bundles/SyliusShopBundle/Product/_box.html.twig` 
path. Check out our [_box.html.twig](tests/Application/templates/bundles/SyliusShopBundle/Product/_box.html.twig) file for a reference.

Note the line: `{% include "@SetonoSyliusCalloutPlugin/Callout/_callouts.html.twig" with {'callouts' : product.callouts|setono_callouts} %}`.

### Step 7: Configure Messenger (optional, but recommended)

This plugin assigns product callouts when the administrator create/update the product or callout. With a large number of products this can result in slower page performance. To circumvent this problem you can use an async transport with Symfony Messenger to assign callouts.

Follow the installation instructions here: [Messenger: Sync & Queued Message Handling](https://symfony.com/doc/current/messenger.html) and then [configure a transport](https://symfony.com/doc/current/messenger.html#transports-async-queued-messages).

Basically you should do:
```bash
$ composer req symfony/messenger symfony/serializer-pack
```

Then configure the Messenger component:
```yaml
# config/packages/messenger.yaml
framework:
    messenger:
        transports:
            amqp: "%env(MESSENGER_TRANSPORT_DSN)%"
```

```yaml
# .env
###> symfony/messenger ###
MESSENGER_TRANSPORT_DSN=amqp://guest:guest@localhost:5672/%2f/messages
###< symfony/messenger ###
```

And finally configure the plugin to use your transport:

```yaml
setono_sylius_callout:
    messenger:
        transport: amqp # Note this is the same key as your transport above
```

After this, the Messenger will use your asynchronous queue when assigning callouts.

### Step 8: Configure cron job
For the performance reasons, configure a cron job on your production server to execute `$ bin/console setono:sylius-callout:assign` command 
once in a while in order to rebuild the index for callouts. In most cases it should be done by the resource event listener
triggered anytime you create/update a product or callout, but it is worth to have it covered if something goes wrong.

### Step 9: Install assets
```bash
$ bin/console assets:install
```

## Usage

From now on you should be able to add new callouts in the admin panel. Once you add one, you just need to configure.

## Customization

Adding a new rule form
----------------------

1. Configure a new form under `App\Form\Type\Rule` namespace like this [IsOnSaleConfigurationType](src/Form/Type/Rule/IsOnSaleConfigurationType.php),
2. Add a rule checker under `App\Checker\Rule` namespace like this [IsOnSaleRuleChecker](src/Checker/Rule/IsOnSaleRuleChecker.php) and
make sure it implements `Setono\SyliusCalloutPlugin\Checker\Rule\ProductCalloutRuleCheckerInterface` interface and has a `public const TYPE` 
set corresponding to the below service configuration 
3. Register and tag new services:
```xml
<!-- services.xml -->
<services>
    ...
    
    <service id="setono_sylius_callout.callout_rule_checker.is_on_sale" class="Setono\SyliusCalloutPlugin\Checker\Rule\IsOnSaleRuleChecker">
        <argument type="service" id="setono_sylius_callout.checker.product_promotion" />
        <tag name="setono_sylius_callout.callout_rule_checker" type="is_on_sale" label="setono_sylius_callout.ui.is_on_sale" form-type="Setono\SyliusCalloutPlugin\Form\Type\Rule\IsOnSaleConfigurationType" />
    </service>
    
    <service id="setono.form.type.rule.is_on_sale" class="Setono\SyliusCalloutPlugin\Form\Type\Rule\IsOnSaleConfigurationType">
        <tag name="form.type" />
    </service>
</services>
```

[ico-version]: https://poser.pugx.org/setono/sylius-callout-plugin/v/stable
[ico-unstable-version]: https://poser.pugx.org/setono/sylius-callout-plugin/v/unstable
[ico-license]: https://poser.pugx.org/setono/sylius-callout-plugin/license
[ico-travis]: https://travis-ci.com/Setono/SyliusCalloutPlugin.svg?branch=master
[ico-code-quality]: https://img.shields.io/scrutinizer/g/Setono/SyliusCalloutPlugin.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/setono/sylius-callout-plugin
[link-travis]: https://travis-ci.com/Setono/SyliusCalloutPlugin
[link-code-quality]: https://scrutinizer-ci.com/g/Setono/SyliusCalloutPlugin
