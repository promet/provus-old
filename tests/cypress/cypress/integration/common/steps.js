import { Given } from "cypress-cucumber-preprocessor/steps";
import { Then } from "cypress-cucumber-preprocessor/steps";

Then(`I should see {string}`, (title) => {
  cy.get('.main').contains(title)
});

Given('I visit {string}', (url) => {
  cy.visit(url)
});

Then('I should see the EDU menu', (url) => {
  cy.get('.header__menu').contains("Degree Programs")
  cy.get('.header__menu').contains("Academic Departments")
});

Given('I visit the .org {string}', (url) => {
  let fullUrl = "http://org." + Cypress.config().baseUrl.slice(7) + url;
  cy.visit(fullUrl)
});

Then('I should see the ORG menu', (url) => {
  cy.get('.header__menu').contains("Patient Care")
  cy.get('.header__menu').contains("For Patients")
  cy.get('.header__menu').contains("Degree Programs").should('not.exist')
  cy.get('.header__menu').contains("Academic Departments").should('not.exist')

});