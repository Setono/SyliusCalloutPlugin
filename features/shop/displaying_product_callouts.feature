@displaying_callouts
Feature: Displaying Product Callouts
    In order to focus on special products
    As a Visitor
    I want to be able to see callouts in the special product catalog

    Background:
        Given I am logged in as an administrator
        And the store operates on a single channel in "United States"
        And the store classifies its products as "T-Shirts", "Funny" and "Sad"
        And the store has a product "PHP T-Shirt"
        And this product belongs to "T-Shirts"
        And the store has a product "Java T-Shirt"
        And this product belongs to "T-Shirts"
        And the store has a product "C++ T-Shirt"
        And this product belongs to "T-Shirts"
        And the store has a product "Plastic Tomato"
        And this product belongs to "Funny"

    @ui
    Scenario: Displaying a product callout based on taxon
        Given there is a callout "Sale" with "Has taxon" rule configured with "T-Shirts" taxon and with "<p>Sale</p>" html
        When I browse products from taxon "T-Shirts"
        Then I should see 3 products with callout "Sale"

    @ui
    Scenario: Displaying a product callout based on product
        Given there is a callout "Good to buy" with "Has product" rule configured with "PHP T-Shirt" product and with "<p>Good to buy</p>" html
        When I browse products from taxon "T-Shirts"
        Then I should see 1 product with callout "Good to buy"

    @ui
    Scenario: Displaying is new callout
        Given there is a callout "New" with "Is new" rule configured with 1 day and with "<p>Just arrived</p>" html
        And the store has a product "Old product" with code "OLD", created at "08-10-2019"
        And this product belongs to "T-Shirts"
        When I browse products from taxon "T-Shirts"
        Then I should see 3 product with callout "New"

    @ui
    Scenario: Callouts not enabled for channel shouldn't be rendered
        Given there is a callout "Disabled" with "Has taxon" rule configured with "T-Shirts" taxon and with "<p>Disabled</p>" html
        And this callout is disabled for "United States" channel
        When I browse products from taxon "T-Shirts"
        Then I should see 0 products with callout "Disabled"
