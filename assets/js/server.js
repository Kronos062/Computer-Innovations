//encender server "node server.js"
//comprobar login "curl -X POST http://localhost:3000/login -H "Content-Type: application/json" -d '{"username":"cristian@naci.es","password":"1234"}'"

const http = require('http');

const users = [
  { username: 'angel@naci.es', password: '1234' },
  { username: 'cristian@naci.es', password: '1234' },
  { username: 'unai@naci.es', password: '1234' }
];

const server = http.createServer((req, res) => {
  res.setHeader('Access-Control-Allow-Origin', '*');
  res.setHeader('Access-Control-Allow-Methods', 'OPTIONS, POST');
  res.setHeader('Access-Control-Allow-Headers', 'Content-Type');

  if (req.method === 'OPTIONS') {
    res.writeHead(204);
    res.end();
    return;
  }

  if (req.method === 'POST' && req.url === '/login') {
    let body = '';

    req.on('data', (chunk) => {
      body += chunk.toString();
    });

    req.on('end', () => {
      const { username, password } = JSON.parse(body);

      const user = users.find(u => u.username === username && u.password === password);

      if (user) {
        res.writeHead(200, { 'Content-Type': 'application/json' });
        res.end(JSON.stringify({ message: 'Inicio de sesión exitoso' }));
      } else {
        res.writeHead(401, { 'Content-Type': 'application/json' });
        res.end(JSON.stringify({ error: 'Credenciales incorrectas' }));
      }
    });
  } else {
    res.writeHead(404, { 'Content-Type': 'text/plain' });
    res.end('Ruta no encontrada');
  }
});

const PORT = 3000;
server.listen(PORT, () => {
  console.log(`Servidor corriendo en http://localhost:${PORT}`);
});
