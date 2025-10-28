const express = require('express');
const mysql = require('mysql2');
const cors = require('cors');
const path = require('path');

const app = express();
const port = 3000;

app.use(cors());
app.use(express.json());
app.use(express.urlencoded({ extended: true }));
app.use(express.static('public'));

const db = mysql.createConnection({
  host: 'localhost',
  user: 'root',
  password: '',
  database: 'bevolkingsregister_db'
});

db.connect(err => {
  if (err) console.error('Databasefout:', err);
  else console.log('Verbonden met de database');
});

// Alle burgers ophalen
app.get('/api/burgers', (req, res) => {
  db.query('SELECT * FROM burgers', (err, results) => {
    if (err) return res.status(500).json({ error: err.message });
    res.json(results);
  });
});

// Nieuwe burger toevoegen
app.post('/api/burgers', (req, res) => {
  const { Voornaam, Achternaam, Geboortedatum, DNA_code, Familie_code } = req.body;
  if (!Voornaam || !Achternaam || !Geboortedatum || !DNA_code || !Familie_code)
    return res.status(400).json({ error: 'Alle velden zijn verplicht' });

  db.query(
    'INSERT INTO burgers (Voornaam, Achternaam, Geboortedatum, DNA_code, Familie_code) VALUES (?, ?, ?, ?, ?)',
    [Voornaam, Achternaam, Geboortedatum, DNA_code, Familie_code],
    (err, result) => {
      if (err) return res.status(500).json({ error: err.message });
      res.json({ success: true, id: result.insertId });
    }
  );
});

// Bestaande burger bewerken
app.put('/api/burgers/:id', (req, res) => {
  const { id } = req.params;
  const { Voornaam, Achternaam, Geboortedatum, DNA_code, Familie_code } = req.body;

  db.query(
    'UPDATE burgers SET Voornaam=?, Achternaam=?, Geboortedatum=?, DNA_code=?, Familie_code=? WHERE Burger_id=?',
    [Voornaam, Achternaam, Geboortedatum, DNA_code, Familie_code, id],
    err => {
      if (err) return res.status(500).json({ error: err.message });
      res.json({ success: true });
    }
  );
});

// Burger verwijderen
app.delete('/api/burgers/:id', (req, res) => {
  const { id } = req.params;
  db.query('DELETE FROM burgers WHERE Burger_id=?', [id], err => {
    if (err) return res.status(500).json({ error: err.message });
    res.json({ success: true });
  });
});

// Frontend routes
app.get('/Burger', (req, res) => res.sendFile(path.join(__dirname, 'public', 'Burger.html')));
app.get('/Ambtenaar', (req, res) => res.sendFile(path.join(__dirname, 'public', 'Ambtenaar.html')));
app.get('/', (req, res) => res.send('<a href="/Burger">Burger</a> | <a href="/Ambtenaar">Ambtenaar</a>'));

app.listen(port, () => console.log(`Server draait op http://localhost:${port}`));
