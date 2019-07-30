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
        Given there is a product callout "Sale" with "Has taxon" rule configured with "T-Shirts" taxon and with "<p>Sale</p>" html
        When I browse products from taxon "T-Shirts"
        Then I should see 3 products with product callout "Sale"

    @ui
    Scenario: Displaying a product callout based on product
      Given there is a product callout "Good to buy" with "Has product" rule configured with "PHP T-Shirt" product and with "<p>Good to buy</p>" html
      When I browse products from taxon "T-Shirts"
      Then I should see 1 product with product callout "Good to buy"
