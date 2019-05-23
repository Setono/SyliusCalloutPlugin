<h1 align="center">
    <a href="https://setono.io" target="_blank">
        <img src="https://setono.io/build/images/logo.jpg" width="35%" />
    </a>
    <br />
    <a href="https://packagist.org/packages/setono/callouts-plugin" title="License" target="_blank">
        <img src="https://img.shields.io/packagist/l/setono/callouts-plugin.svg" />
    </a>
    <a href="https://packagist.org/packages/setono/callouts-plugin" title="Version" target="_blank">
        <img src="https://img.shields.io/packagist/v/setono/callouts-plugin.svg" />
    </a>
    <a href="http://travis-ci.org/Setono/SyliusCalloutsPlugin" title="Build status" target="_blank">
            <img src="https://img.shields.io/travis/Setono/SyliusCalloutsPlugin/master.svg" />
        </a>
    <a href="https://scrutinizer-ci.com/g/Setono/SyliusCalloutsPlugin/" title="Scrutinizer" target="_blank">
        <img src="https://img.shields.io/scrutinizer/g/Setono/SyliusCalloutsPlugin.svg" />
    </a>
    <a href="https://packagist.org/packages/setono/callouts-plugin" title="Total Downloads" target="_blank">
        <img src="https://poser.pugx.org/setono/callouts-plugin/downloads" />
    </a>
</h1>

## Overview

The product callouts plugin for [Sylius](https://sylius.com/) allows you to configure nice badges for different set of products
based on specific rules. It provides a common set of configuration by default and is very flexible when it comes to adding new ones.

## Installation
```bash
$ composer require setono/callouts-plugin
```
    
1. Add plugin dependencies to your bundles.php file:
```php
$bundles = [
    ...
    
    OldSound\RabbitMqBundle\OldSoundRabbitMqBundle::class => ['all' => true],
    Setono\SyliusCalloutsPlugin\SetonoSyliusCalloutsPlugin:class => ['all' => true],
]);
```

2. Import required config in your `app/config/config.yml` file:
```yaml
# config/packages/_sylius.yaml

imports:
    ...
    
    - { resource: "@SetonoSyliusCalloutsPlugin/Resources/config/config.yml" }
```

3. Import routing **on top** of your `app/config/routing.yml` file:
```yaml

# config/routes/routes.yaml

setono_product_callouts_plugin:
    resource: "@SetonoSyliusCalloutsPlugin/Resources/config/routing.yml"
```

4. Install assets
```bash
$ bin/console assets:install --symlink
```

5. Customize your product model. Read more about Sylius models customization [here](https://docs.sylius.com/en/latest/customization/model.html).
- add a `Setono\SyliusCalloutsPlugin\Model\CalloutsAwareTrait` trait to your `App\Entity\Product` class (check our [this path](tests/Application/src) for a reference),
- add callouts relation to your `Product.orm.xml` like [here](tests/Application/src/Resources/config/doctrine),
- if you haven't done so already, configure the `sylius_product` resource to point to your `App\Entity\Product` like we 
did in an example [here](tests/Application/src/Resources/config/resources.yml).

**Note:** We are using `.orm.xml` file format for entities configuration. You can use whatever format you wish. For more details
read the official [Symfony Doctrine configuration reference](https://symfony.com/doc/current/reference/configuration/doctrine.html) or
check out our configuration [here](tests/Application/config/packages/doctrine.yaml).

6. Add callouts to your product box template. By default, you should use `templates/bundles/SyliusShopBundle/Product/_box.html.twig` 
path. Check out our [_box.html.twig](tests/Application/templates/bundles/SyliusShopBundle/Product/_box.html.twig) file for a reference.
Note the `setono_render_callouts` Twig function that uses `Setono\SyliusCalloutsPlugin\Model\CalloutsAwareInterface` as a first parameter
and `position` as a second one. 
Currently available positions are:
* `top_left_corner`
* `top_right_corner`
* `bottom_right_corner`
* `bottom_left_corner`

7. Configure a local connection to the RabbitMQ by
- Adding a RabbitMQ URL in your `.env` file:
```text
# .env

###> setono/sylius-callouts-plugin ###
RABBITMQ_URL=amqp://guest:guest@localhost:5672
###< setono/sylius-callouts-plugin ###
``` 
- Adding a [setono_sylius_callouts_plugin.yaml](tests/Application/config/packages/setono_sylius_callouts_plugin.yaml)
configuration file to your `config/packages` directory.

```yaml
imports:
    - { resource: "@SetonoSyliusCalloutsPlugin/Resources/config/config.yml"}

old_sound_rabbit_mq:
    connections:
        default:
            url: '%env(resolve:RABBITMQ_URL)%'
    producers:
        callouts:
            connection: default
            exchange_options:
                name: 'product'
                type: direct
    consumers:
        callouts:
            connection: default
            exchange_options:
                name: 'product'
                type: direct
            queue_options:
                name: 'product'
            callback: setono_sylius_callouts_plugin.consumer.product_callouts_assigner
            enable_logger: true
```

8. For the performance reasons, configure a cron job on your production server to execute `$ bin/console setono:callouts:assign` command 
once in a while in order to rebuild the index for callouts. In most cases it should be done by the resource event listener
triggered anytime you create/update a product or callout, but it is worth to have it covered if something goes wrong.

## Usage

From now on you should be able to add new callouts in the admin panel. Once you add one, you just need to configure.

## Customization

Adding a new rule form
----------------------

1. Configure a new form under `App\Form\Type\Rule` namespace like this [IsOnSaleConfigurationType](src/Form/Type/Rule/IsOnSaleConfigurationType.php),
2. Add a rule checker under `App\Checker\Rule` namespace like this [IsOnSaleRuleChecker](src/Checker/Rule/IsOnSaleRuleChecker.php) and
make sure it implements `Setono\SyliusCalloutsPlugin\Checker\Rule\ProductCalloutRuleCheckerInterface` interface and has a `public const TYPE` 
set corresponding to the below service configuration 
3. Register and tag new services:
```xml
<!-- services.xml -->
<services>
    ...
    
    <service id="setono_sylius_callouts_plugin.callout_rule_checker.is_on_sale" class="Setono\SyliusCalloutsPlugin\Checker\Rule\IsOnSaleRuleChecker">
        <argument type="service" id="setono_sylius_callouts_plugin.checker.product_promotion" />
        <tag name="setono_sylius_callouts_plugin.callout_rule_checker" type="is_on_sale" label="setono_sylius_callouts_plugin.ui.is_on_sale" form-type="Setono\SyliusCalloutsPlugin\Form\Type\Rule\IsOnSaleConfigurationType" />
    </service>
    
    <service id="setono.form.type.rule.is_on_sale" class="Setono\SyliusCalloutsPlugin\Form\Type\Rule\IsOnSaleConfigurationType">
        <tag name="form.type" />
    </service>
</services>
```
 
### Available services you can [decorate](https://symfony.com/doc/current/service_container/service_decoration.html) and forms you can [extend](http://symfony.com/doc/current/form/create_form_type_extension.html)

```bash
$ bin/console debug:container | grep setono_sylius_callouts_plugin
```

### Running plugin tests

- PHPSpec
```bash
$ vendor/bin/phpspec run
```

- Behat (non-JS scenarios)
```bash
$ vendor/bin/behat --tags="~@javascript"
```

- Behat (JS scenarios)
1. Download [Chromedriver](https://sites.google.com/a/chromium.org/chromedriver/)
2. Download [Selenium Standalone Server](https://www.seleniumhq.org/download/).
2. Run Selenium server with previously downloaded Chromedriver:
```bash
$ java -Dwebdriver.chrome.driver=chromedriver -jar selenium-server-standalone.jar
```

3. Run test application's webserver on `localhost:8080`:
```bash
$ (cd tests/Application && bin/console server:run localhost:8080 -d public -e test)
```

4. Run Behat:
```bash
$ vendor/bin/behat
```

### Opening Sylius with your plugin

- Using `test` environment:
```bash
$ (cd tests/Application && bin/console sylius:fixtures:load -e test)
$ (cd tests/Application && bin/console server:run -d public -e test)
```

- Using `dev` environment:
```bash
$ (cd tests/Application && bin/console sylius:fixtures:load -e dev)
$ (cd tests/Application && bin/console server:run -d public -e dev)
```

## Contribution

Learn more about our contribution workflow on http://docs.sylius.org/en/latest/contributing/.
