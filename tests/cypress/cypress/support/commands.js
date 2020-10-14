Cypress.Commands.add('getRestToken', (user, password) => {
  cy.login(user, password);
  return cy.request({
    method: 'GET',
    url: '/session/token',
  }).its('body');
});

Cypress.Commands.add('login', (user, password) => {
  return cy.request({
    method: 'POST',
    url: '/user/login', 
    form: true,
    body: { 
      name: user,
      pass: password,
      form_id: 'user_login_form' 
    }
  });
});

Cypress.Commands.add('logout', () => {
  return cy.request('/user/logout');
});