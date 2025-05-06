document.addEventListener('DOMContentLoaded', cargarTickets);

async function cargarTickets() {
  const res = await fetch('backend/get_tickets.php');
  const tickets = await res.json();
  const contenedor = document.getElementById('ticket-list');
  contenedor.innerHTML = '';

  tickets.forEach(ticket => {
    const ticketHTML = `
      <div class="ticket-item d-flex justify-content-between align-items-start border p-3 mb-2 rounded">
        <div>
          <h4>${ticket.titulo}</h4>
          <p><strong>Descripción:</strong> ${ticket.descripcion}</p>
          <p><strong>Lugar:</strong> ${ticket.lugar}</p>
          <p><strong>Afectado:</strong> ${ticket.afectado}</p>
          <p><strong>Prioridad:</strong> ${ticket.prioridad}</p>
        </div>
        <div>
          <button onclick="eliminarTicket(${ticket.id})" class="btn btn-danger">Eliminar</button>
        </div>
      </div>
    `;
    contenedor.innerHTML += ticketHTML;
  });
}

async function crearTicket() {
  const data = {
    titulo: document.getElementById('titulo').value,
    descripcion: document.getElementById('descripcion').value,
    lugar: document.getElementById('lugar').value,
    prioridad: document.getElementById('prioridad').value,
  };

  const res = await fetch('backend/create_ticket.php', {
    method: 'POST',
    body: JSON.stringify(data),
    headers: { 'Content-Type': 'application/json' }
  });

  if (res.ok) {
    alert('Ticket creado con éxito');
    document.getElementById('ticketForm').reset();
    cargarTickets();
  } else {
    alert('Error al crear el ticket');
  }
}

async function eliminarTicket(id) {
  if (!confirm("¿Seguro que deseas eliminar este ticket?")) return;

  const res = await fetch(`backend/delete_ticket.php?id=${id}`);
  if (res.ok) {
    cargarTickets();
  } else {
    alert('Error al eliminar el ticket');
  }
}

function limpiarFormulario() {
  document.getElementById('ticketForm').reset();
}