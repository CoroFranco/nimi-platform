describe('Buscar Cursos', () => {
    it('El usuario deberia poder buscar cursos y que se muestren correctamente en tiempo real', () => {
        cy.visit('http://127.0.0.1:8000');

        cy.get('input[name="email"]').type('ejemplo@ejemplo.com')

        cy.get('input[name="password"]').clear().type('123456789')

        cy.get('#loginButton').click()

        cy.contains('Explorar').click()

        cy.get('#search').type('nuevo')

        cy.contains('Nuevo curso').should('be.visible');

    })
})