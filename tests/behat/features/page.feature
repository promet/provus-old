@api
Feature: Test pages 

Scenario: Ensure test page contains promo card 
  Given I am on "/landing-page/internal-page-blocks"
  Then I should see "My Account"
