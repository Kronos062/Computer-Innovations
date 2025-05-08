document.addEventListener('DOMContentLoaded', cargarTickets);

async function crearTicket() {
  const data = {
    titulo: document.getElementById('titulo').value,
    descripcion: document.getElementById('descripcion').value,
    lugar: document.getElementById('lugar').value,
    prioridad: document.getElementById('prioridad').value,
  };
  console.log(data);
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