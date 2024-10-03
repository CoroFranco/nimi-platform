describe('Actualizar datos de usuario', () => {
    it('Deberia actualizar los datos del usuario y mostrar que se actualizaron correctamente', () => {
        cy.visit('http://127.0.0.1:8000');

        cy.get('input[name="email"]').type('ejemplo@ejemplo.com')

        cy.get('input[name="password"]').clear().type('123456789')

        cy.get('#loginButton').click()

        cy.contains('ejemplo').click()

        cy.contains('Nuvo curso').click()

        cy.contains('Mark as Completed').click() 

        cy.contains('Lesson Completed').should('be.visible');
        cy.contains('Next Lesson').click()

        cy.contains('no').click()
        cy.contains('Submit Quiz').click()
        cy.contains('Next Lesson').click()

        cy.contains('Mark as Completed').click() 
        cy.contains('Lesson Completed').should('be.visible');
    })
})