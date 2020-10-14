@api
Feature: Test landing page content type 

Scenario: Ensure homepage working 
  Given I am on "/"
  Then I should see "Welcome to Southport County" 
