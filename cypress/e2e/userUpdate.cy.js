describe('Actualizar datos de usuario', () => {
    it('Deberia actualizar los datos del usuario y mostrar que se actualizaron correctamente', () => {
        cy.visit('http://127.0.0.1:8000');

        cy.get('input[name="email"]').type('ejemplo@ejemplo.com')

        cy.get('input[name="password"]').clear().type('123456789')

        cy.get('#loginButton').click()

        cy.contains('ejemplo').click()

        cy.contains('Editar perfil').click()

        cy.get('input[name="name"]').clear().type('Juan')
        cy.get('input[name="email"]').clear().type('Juan@ejemplo.com')
        cy.get('textarea[name="bio"]').clear().type('Mi biografía')

        cy.get('#saveChangeBtn').click()

        cy.contains('Usuario actualizado exitosamente.').should('be.visible');

    })
})