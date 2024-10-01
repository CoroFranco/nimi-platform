describe('Registro de Usuario', () => {
    it('Debería mostrar errores al registrarse', () => {
      cy.visit('http://127.0.0.1:8000');
  
      cy.contains('¿No tienes cuenta? Regístrate').click();
  
      cy.get('input[name="registerName"]').type('Juan');
      cy.get('input[name="registerEmail"]').type('correo@correo.com');
      cy.get('input[name="registerPassword"]').type('123456789');
      cy.get('input[name="registerReapetPassword"]').type('123456789');
  
      cy.get('#registerButton').click()
  
      cy.contains('Este correo electrónico ya está registrado.').should('be.visible');

      cy.get('input[name="registerEmail"]').clear().type('correo@correo123.com');
      cy.get('input[name="registerReapetPassword"]').clear().type('12345678');

      cy.get('#registerButton').click()
  
      cy.contains('Las contraseñas no coinciden.').should('be.visible');
    });
  });