describe('Registro de Usuario', () => {
  it('Debería permitir al usuario registrarse', () => {
    cy.visit('http://127.0.0.1:8000');

    cy.contains('¿No tienes cuenta? Regístrate').click();

    cy.get('input[name="registerName"]').type('Juan');
    cy.get('input[name="registerEmail"]').type('juan@example2.com');
    cy.get('input[name="registerPassword"]').type('12345678');
    cy.get('input[name="registerReapetPassword"]').type('12345678');

    cy.get('#registerButton').click()

    cy.contains('Cuenta creada correctamente. Por favor, inicia sesión.').should('be.visible');

  });
});