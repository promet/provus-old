@api
Feature: Test sitemap page

Scenario: Ensure sitemap page is working
  Given I am on "/contact/sitemap"
  Then I should see "Sitemap"
