@api
Feature: Test webforms

Scenario: Ensure Library forms visible
  Given I am on "/form/libraries-contact"
  Then I should see "Email Address"
  Given I am on "/form/contact"
  Then I should see "Your Email"
