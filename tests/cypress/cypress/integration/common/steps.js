import { Given } from "cypress-cucumber-preprocessor/steps";
import { Then } from "cypress-cucumber-preprocessor/steps";

Then(`I should see {string}`, (title) => {
  cy.get('body').contains(title)
});

Given('I visit {string}', (url) => {
  cy.visit(url)
});

Given('I visit the Workbench Dashboard', (url) => {
  cy.visit('/admin/workbench')
});

Then('I should see the EDU menu', (url) => {
  cy.get('.header__menu').contains("Degree Programs")
  cy.get('.header__menu').contains("Academic Departments")
});

Given('I visit the .org {string}', (url) => {
  let fullUrl = "http://org." + Cypress.config().baseUrl.slice(7) + url;
  cy.visit(fullUrl)
});

Given('I am logged in as {string}', (user) => {
  cy.visit('/user');
  cy.get("#block-provus-page-title").then(title => {
    const text = title.text().trim();

    // Check to see if already logged in.
    if (text == user) {
      cy.log("User already logged in.")
      return;
    }
    else if (text == 'Log in') {
      cy.get('#edit-name').type(user);
      cy.get('#edit-pass').type(user);
      cy.get('#user-login-form #edit-submit').click();  
    }
    else {
      cy.log("This should be " + text);
      // Logout if wrong user.
      cy.visit('/user/logout');
      cy.visit('/user');
      // Log in with right user.
      cy.get('#edit-name').type(user);
      cy.get('#edit-pass').type(user);
      cy.get('#user-login-form #edit-submit').click();  
    }

  });

});