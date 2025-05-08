document.addEventListener("DOMContentLoaded", () => {
  fetch('../php/obtener_tickets.php')
    .then(response => response.json())
    .then(data => {
      const container = document.getElementById('ticket-list');
      container.innerHTML = '';

      if (data.error) {
        container.innerHTML = `<p>${data.error}</p>`;
        return;
      }

      if (data.length === 0) {
        container.innerHTML = '<p>No tienes tickets abiertos.</p>';
        return;
      }

      data.forEach(ticket => {
        const div = document.createElement('div');
        div.className = 'ticket';
        div.innerHTML = `
          <h4>${ticket.titulo}</h4>
          <p>${ticket.descripcion}</p>
          <p><strong>Lugar:</strong> ${ticket.lugar}</p>
          <p><strong>Afectado:</strong> ${ticket.afectado}</p>
          <p><strong>Prioridad:</strong> ${ticket.prioridad}</p>
        `;
        container.appendChild(div);
      });
    })
    .catch(error => {
      document.getElementById('ticket-list').innerHTML = '<p>Error al cargar los tickets.</p>';
    });
});