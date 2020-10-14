describe('Homepage Logged in', function() {
  it('logs out via ui', function(){
    cy.login(Cypress.env('cyAdminUser'), Cypress.env('cyAdminPassword'));
    cy.server();
  });
});
describe('Homepage', function() {
  it('visits homepage', function() {
    cy.visit('/');
    cy.get('#block-provus-content').contains('Welcome to Southport County');
  });
});
