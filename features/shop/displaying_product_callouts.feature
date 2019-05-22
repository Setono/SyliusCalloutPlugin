@displaying_product_callouts
Feature: Displaying Product Callouts
    In order to focus on special products
    As a Visitor
    I want to be able to see callouts in the special product catalog

    Background:
        Given I am logged in as an administrator
        And the store operates on a single channel in "United States"

    @ui
    Scenario: Adding a product callout
        Given there are 4 products in the store
        And there is a callout for each position in the store
        And these callouts are associated with 4 recent products
        When I go to the homepage
        Then I should see 4 products with 4 callouts on each position
