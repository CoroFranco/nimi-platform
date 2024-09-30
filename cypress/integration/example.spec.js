describe('Mi primer test', () => {
    it('Visita la página de inicio', () => {
      cy.visit('http://127.0.0.1:8000/'); // Cambia a la URL de tu aplicación
      cy.contains('Texto esperado').should('be.visible'); // Cambia a un texto que esperas ver
    });
  });