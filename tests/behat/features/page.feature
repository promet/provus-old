@api
Feature: Test pages 

Scenario: Ensure test page contains promo card 
  Given I am on "/people"
  Then I should see "The people working for you."
