# Upgrade documentation

## Upgrade from 0.4 to 0.5

The plugin has been refactored a lot between 0.4 and 0.5. Here is a list of things you (may) have to do to upgrade your project:

### Configuration changes

- The `setono_sylius_callout.manual_triggering` configuration key has been removed.
- The `setono_sylius_callout.no_rules_eligible` configuration key has been removed.
- The `setono_sylius_callout.elements` configuration key has been added. This is a list of elements that you can
  associate callouts with. For example, if you have a product page with an image and a description area, you
  could have a callout that is only shown on the image and another callout that is only shown in the description area.
  The `elements` configuration key is a list of these elements. You can add as many as you want, but you must have at
  least one. The default value is `["default"]`.

### Entity changes

- An `$elements` property was added to the `Callout` entity. This is to allow callouts to be associated with different
elements in your layout. If you don't want to use this feature, you should set the value `["default"]` in your migration
file because the `$elements` property is required and is a json list.

- The `ProductInterface` was also updated and instead of using the `CalloutsAwareTrait` in your `Product` entity, you
should now use the `ProductTrait`.

Here's an SQL migration example:

```sql
DROP TABLE setono_sylius_callout__product_callouts;
ALTER TABLE sylius_product ADD pre_qualified_callouts LONGTEXT DEFAULT NULL COMMENT '(DC2Type:json)';
ALTER TABLE setono_sylius_callout__callout ADD version INT DEFAULT 1 NOT NULL, ADD elements LONGTEXT DEFAULT NULL COMMENT '(DC2Type:json)', ADD color VARCHAR(255) DEFAULT NULL, ADD background_color VARCHAR(255) DEFAULT NULL, DROP rules_assigned_at, CHANGE code code VARCHAR(255) NOT NULL, CHANGE position position VARCHAR(255) NOT NULL, CHANGE created_at created_at DATETIME NOT NULL;
UPDATE setono_sylius_callout__callout SET elements = '["default"]';
ALTER TABLE setono_sylius_callout__callout CHANGE elements elements LONGTEXT NOT NULL COMMENT '(DC2Type:json)';
```

Notice that in the first alteration of `setono_sylius_callout__callout` we allow `null` on the `elements` column
and then proceed to set the value to `["default"]`. This is because the `elements` property is required and is a json list.

Finally, we make it required and not null.

Also notice that you want to use one of the elements you defined in `setono_sylius_callout.elements`.

### Routing changes

- The routes file was renamed from `routing.yaml` to `routes.yaml`, so your `config/routes/setono_sylius_callout.yaml`
should look like this:

    ```yaml
    setono_sylius_callout:
        resource: "@SetonoSyliusCalloutPlugin/Resources/config/routes.yaml"
    ```

### Twig extension changes

- The `setono_callouts` filter has been removed. This was used to get the callouts, and you should use
  the new `get_callouts(ProductInterface $product, string $element = null)` function instead.
  See an example [here](tests/Application/templates/bundles/SyliusShopBundle/Product/_box.html.twig).
