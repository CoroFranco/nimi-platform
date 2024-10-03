describe('Login de usuario', () => {
    it('Deberia llevar al usuario hacia la pagina Home si sus credenciales son correctas', () => {
        cy.visit('http://127.0.0.1:8000');

        cy.get('input[name="email"]').type('ejemplo@ejemplo.com')
        cy.get('input[name="password"]').type('123456')


        cy.get('#loginButton').click()

        cy.contains('Contraseña incorrecta.').should('be.visible');

        cy.get('input[name="password"]').clear().type('123456789')

        cy.get('#loginButton').click()

        cy.url().should('include', 'http://127.0.0.1:8000/home');
    })
})