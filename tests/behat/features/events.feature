@api
Feature: Test events 

Scenario: Ensure calendar loaded 
  Given I am on "/events"
  Then I should see "Test Event 3"
