Feature: Workflow

  Scenario: Workbench Dashboard
  Given I am logged in as "contenteditor"
  When I visit the Workbench Dashboard
  Then I should see "My Workbench"