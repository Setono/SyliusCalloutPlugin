@managing_product_callouts
Feature: Adding Product Callouts
    In order to display additional info for a product to customers
    As an Administrator
    I want to be able to add new product callouts

    Background:
        Given I am logged in as an administrator
        And the store operates on a single channel in "United States"

    @ui
    Scenario: Adding a product callout
        When I go to the create product callout page
        And I fill the priority with 1
        And I specify its position as "Top left corner"
        And I specify its code as "new_collection"
        And I fill the html with "<span>NEW</span>"
        And I name it "New collection callout"
        And I add 2 products as a product rule
        And I add 2 taxons as a taxon rule
        And I enable the on sale rule
        And I add it
        Then I should be notified that it has been successfully created
