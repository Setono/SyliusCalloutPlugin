@managing_callouts
Feature: Adding Product Callouts
    In order to display additional info for a product to customers
    As an Administrator
    I want to be able to add new product callouts

    Background:
        Given I am logged in as an administrator
        And the store operates on a single channel in "United States"
        And the store classifies its products as "T-Shirts" and "Mugs"
        And the store has a product "PHP T-Shirt"

    @ui @javascript
    Scenario: Adding a product callout
        When I go to the create product callout page
        And I fill the priority with 1
        And I specify its position as "Top left corner"
        And I specify its code as "new_collection"
        And I fill the text with "NEW"
        And I name it "New collection callout"
        And I add the "Has taxon" rule configured with "T-Shirts" and "Mugs"
        And I add the "Has product" rule configured with the "PHP T-Shirt" product
        And I add it
        Then I should be notified that it has been successfully created
