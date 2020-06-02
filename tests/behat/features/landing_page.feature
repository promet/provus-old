@api
Feature: Test landing page content type 

Scenario: Ensure homepage working 
  Given I am on "/landing-page/home-page"
  Then I should see "Welcome to Southport County" 
  And I should see "Cal Optima Recruitment" 
