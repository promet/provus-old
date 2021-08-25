@api
Feature: Test News and Press Releases pages

Scenario: Ensure News page exists 
  Given I am on "/news"
  Then I should see "News"
  And I should see "What is Atomic Web Design?"
