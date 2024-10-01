describe('Navegacion y logout', () => {
    it('El usuario puede navegar por todas las paginas y puede hacer logout', () => {
        cy.visit('http://127.0.0.1:8000');

        cy.get('input[name="email"]').type('ejemplo@ejemplo.com')
        cy.get('input[name="password"]').type('123456789')

        cy.get('#loginButton').click()

        cy.get('#loginButton').click()

        cy.url().should('include', 'http://127.0.0.1:8000/home');

        cy.contains('Explorar').click()

        cy.url().should('include', 'http://127.0.0.1:8000/explorer');

        cy.get('#profileNav').click()

        cy.url().should('include', 'http://127.0.0.1:8000/home/profile');

        cy.get('#logoutNav').click()

        cy.url().should('include', 'http://127.0.0.1:8000')

    })
})