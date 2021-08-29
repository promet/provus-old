@api
Feature: Test pages 

Scenario: Ensure test page contains promo card 
  Given I am on "/node/18"
  Then I should see "Staff"
