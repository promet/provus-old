describe('Login', function() {
  it('logs in via ui', function(){
    cy.visit('/user/login');
    cy.get('#block-provus-page-title').contains('Log in');
    cy.get('#edit-name').type(Cypress.env('cyAdminUser'));
    cy.get('#edit-pass').type(Cypress.env('cyAdminPassword'));
    cy.get('#user-login-form #edit-submit').click();
  });
});
