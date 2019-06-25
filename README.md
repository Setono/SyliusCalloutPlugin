# Sylius Callout Plugin

[![Latest Version][ico-version]][link-packagist]
[![Latest Unstable Version][ico-unstable-version]][link-packagist]
[![Software License][ico-license]](LICENSE)
[![Build Status][ico-travis]][link-travis]
[![Quality Score][ico-code-quality]][link-code-quality]

The callout plugin for [Sylius](https://sylius.com/) allows you to configure nice badges for different set of products
based on specific rules. It provides a common set of configuration by default and is very flexible when it comes to adding new ones.

## Installation
```bash
$ composer require setono/sylius-callout-plugin
```
    
1. Add plugin dependencies to your `config/bundles.php` file:
```php
<?php
return [
    // ...
    
    Setono\SyliusCalloutPlugin\SetonoSyliusCalloutPlugin::class => ['all' => true],
    
    // ...
];
```

2. Import config:
```yaml
# config/packages/_sylius.yaml

imports:
    ...
    
    - { resource: "@SetonoSyliusCalloutPlugin/Resources/config/config.yml" }
```

3. Import routing:

```yaml
# config/routes/routes.yaml

setono_product_callout:
    resource: "@SetonoSyliusCalloutPlugin/Resources/config/routing.yaml"
```

4. Install assets
```bash
$ bin/console assets:install
```

5. Customize your product model. Read more about Sylius models customization [here](https://docs.sylius.com/en/latest/customization/model.html).
- add a `Setono\SyliusCalloutPlugin\Model\CalloutsAwareTrait` trait to your `App\Entity\Product` class (check our [this path](tests/Application/src) for a reference),
- add callouts relation to your `Product.orm.xml` like [here](tests/Application/src/Resources/config/doctrine),
- if you haven't done so already, configure the `sylius_product` resource to point to your `App\Entity\Product` like we 
did in an example [here](tests/Application/src/Resources/config/resources.yml).

**Note:** We are using `.orm.xml` file format for entities configuration. You can use whatever format you wish. For more details
read the official [Symfony Doctrine configuration reference](https://symfony.com/doc/current/reference/configuration/doctrine.html) or
check out our configuration [here](tests/Application/config/packages/doctrine.yaml).

6. Add callouts to your product box template. By default, you should use `templates/bundles/SyliusShopBundle/Product/_box.html.twig` 
path. Check out our [_box.html.twig](tests/Application/templates/bundles/SyliusShopBundle/Product/_box.html.twig) file for a reference.
Note the `setono_render_callouts` Twig function that uses `Setono\SyliusCalloutPlugin\Model\CalloutsAwareInterface` as a first parameter
and `position` as a second one. 
Currently available positions are:
* `top_left_corner`
* `top_right_corner`
* `bottom_right_corner`
* `bottom_left_corner`

7. Configure Async assigns product callouts

    This plugin assigns product callouts when the administrator create/update the product or callout. With a large number of products this can result in slower page performance. To circumvent this problem you can use an async transport with Symfony Messenger to assigns product callouts.
    
    Follow the installation instructions here: [How to Use the Messenger](https://symfony.com/doc/current/messenger.html) and then [configure a transport](https://symfony.com/doc/current/messenger.html#transports).
    
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
            transport: amqp
    ```
    
    After this, the Messenger will be automatically enabled in this plugin and subsequently, it will assign product callouts asynchronously.

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
 
### Available services you can [decorate](https://symfony.com/doc/current/service_container/service_decoration.html) and forms you can [extend](http://symfony.com/doc/current/form/create_form_type_extension.html)

```bash
$ bin/console debug:container | grep setono_sylius_callout
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

[ico-version]: https://poser.pugx.org/setono/sylius-callout-plugin/v/stable
[ico-unstable-version]: https://poser.pugx.org/setono/sylius-callout-plugin/v/unstable
[ico-license]: https://poser.pugx.org/setono/sylius-callout-plugin/license
[ico-travis]: https://travis-ci.com/Setono/SyliusCalloutPlugin.svg?branch=master
[ico-code-quality]: https://img.shields.io/scrutinizer/g/Setono/SyliusCalloutPlugin.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/setono/sylius-callout-plugin
[link-travis]: https://travis-ci.com/Setono/SyliusCalloutPlugin
[link-code-quality]: https://scrutinizer-ci.com/g/Setono/SyliusCalloutPlugin
