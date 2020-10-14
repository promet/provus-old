@api
Feature: Test News and Press Releases pages

Scenario: Ensure News page exists 
  Given I am on "/news"
  Then I should see "News"

Scenario: Ensure Press Releases page exists 
  Given I am on "/press"
  Then I should see "Press Release"
