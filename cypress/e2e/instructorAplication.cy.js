
describe('Solicitar ser instructor', () => {
    it('El usuario deberia poder enviar una solicitud de ser instructor', () => {
        cy.visit('http://127.0.0.1:8000');

        cy.get('input[name="email"]').type('juan@ejemplo.com')

        cy.get('input[name="password"]').clear().type('123456789')

        cy.get('#loginButton').click()

        cy.contains('Enseñar en Nimi').click()

        cy.get('textarea[name="bio"]').clear().type('Mi biografía debe tener al menos ')
        cy.get('input[name="expertise"]').clear().type('Mi experiencia')
        cy.get('select[name="teaching_experience"]').select('Experto')
        cy.get('input[name="sample_video"]').clear().type('https://www.youtube.com/watch?v=dQw4w9WgXcQ')
        cy.get('#linkedin').clear().type('https://www.linkedin.com/in/ejemplo/')
        cy.get('#twitter').clear().type('https://twitter.com/ejemplo')
        cy.get('#website').clear().type('https://ejemplo.com')

        cy.get('#terms').click()

        cy.contains('Enviar Solicitud').click()

        cy.contains('Tu solicitud para ser instructor ha sido enviada. Te contactaremos pronto.').should('be.visible');
       
        cy.contains('Inicio').click()
        cy.contains('Enseñar en Nimi').click()

        cy.get('textarea[name="bio"]').clear().type('Mi biografía debe tener al menos ')
        cy.get('input[name="expertise"]').clear().type('Mi experiencia')
        cy.get('select[name="teaching_experience"]').select('Experto')
        cy.get('input[name="sample_video"]').clear().type('https://www.youtube.com/watch?v=dQw4w9WgXcQ')
        cy.get('#linkedin').clear().type('https://www.linkedin.com/in/ejemplo/')
        cy.get('#twitter').clear().type('https://twitter.com/ejemplo')
        cy.get('#website').clear().type('https://ejemplo.com')
        cy.get('#terms').click()

        cy.contains('Enviar Solicitud').click()

        cy.contains('Ya tienes una solicitud en proceso por favor espera respuesta.').should('be.visible');

    })
})