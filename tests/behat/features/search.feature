@api
Feature: Search page 

Scenario: Ensure search page exists 
  Given I am on "/search"
  Then I should see "Type"
  And I should see "Sort by"
  And I should see "Order"
