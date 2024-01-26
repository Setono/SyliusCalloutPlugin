# Upgrade documentation

## Upgrade from 0.4 to 0.5

The plugin has been refactored a lot between 0.4 and 0.5. Here is a list of things you (may) have to do to upgrade your project:

### Entity changes

- An `$elements` property was added to the `Callout` entity. This is to allow callouts to be associated with different
elements in your layout. If you don't want to use this feature, you should set the value `["default"]` in your migration
file because the `$elements` property is required and is a json list.

- The `ProductInterface` was also updated and instead of using the `CalloutsAwareTrait` in your `Product` entity, you
should now use the `ProductTrait`.

### Routing changes

- The routes file was renamed from `routing.yaml` to `routes.yaml`, so your `config/routes/setono_sylius_callout.yaml`
should look like this:

    ```yaml
    setono_sylius_callout:
        resource: "@SetonoSyliusCalloutPlugin/Resources/config/routes.yaml"
    ```
