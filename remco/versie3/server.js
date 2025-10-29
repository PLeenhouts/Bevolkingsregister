
const express = require('express');
const mysql = require('mysql2');
const app = express();
app.use(express.json());
app.use(express.static(__dirname));

const db = mysql.createConnection({
  host: 'localhost',
  user: 'root',
  password: '',
  database: 'bevolkingsregister_db'
});

// ➕ Burger toevoegen
app.post('/burger', (req, res) => {
  const { voornaam, achternaam, geboortedatum } = req.body;
  db.query('INSERT INTO burger (voornaam, achternaam, geboortedatum) VALUES (?, ?, ?)',
    [voornaam, achternaam, geboortedatum],
    (err, result) => {
      if (err) return res.status(500).send(err);
      res.send({ id_code: result.insertId });
    });
});
// 🔍 Burgergegevens ophalen op basis van ID
app.get('/burger/:id', (req, res) => {
  const id = req.params.id;
  db.query('SELECT voornaam, achternaam, geboortedatum FROM burger WHERE id_code = ?', [id], (err, results) => {
    if (err) return res.status(500).send(err);
    if (results.length === 0) return res.status(404).send({ error: 'Burger niet gevonden' });
    res.send(results[0]);
  });
});

// ✏️ Burger wijzigen
app.put('/burger/:id', (req, res) => {
  const { voornaam, achternaam, geboortedatum } = req.body;
  db.query('UPDATE burger SET voornaam=?, achternaam=?, geboortedatum=? WHERE id_code=?',
    [voornaam, achternaam, geboortedatum, req.params.id],
    (err) => {
      if (err) return res.status(500).send(err);
      res.send({ status: 'gewijzigd' });
    });
});

// 💍 Huwelijk invoeren + inteeltcontrole
app.post('/huwelijk', async (req, res) => {
  const { partner1_id, partner2_id } = req.body;

  const isIngefokt = await checkInteelt(partner1_id, partner2_id);
  if (isIngefokt) {
    return res.status(400).send({ alarm: 'Inteelt tot 3de graad gedetecteerd!' });
  }

  db.query('INSERT INTO huwelijk (partner1_id, partner2_id) VALUES (?, ?)',
    [partner1_id, partner2_id],
    (err) => {
      if (err) return res.status(500).send(err);
      res.send({ status: 'huwelijk geregistreerd' });
    });
});

// 👶 Kind toevoegen
app.post('/kind', (req, res) => {
  const { voornaam, geboortedatum, vader_id, moeder_id } = req.body;
  db.query(
    'INSERT INTO burger (voornaam, geboortedatum, vader_id, moeder_id) VALUES (?, ?, ?, ?)',
    [voornaam, geboortedatum, vader_id, moeder_id],
    (err, result) => {
      if (err) return res.status(500).send(err);
      res.send({ status: 'kind toegevoegd', id_code: result.insertId });
    }
  );
});

// 🧬 Inteeltcontrole tot 3de graad
async function checkInteelt(id1, id2) {
  async function getAncestors(id, depth = 3) {
    const ancestors = new Set();
    let currentGeneration = [{ id, level: 0 }];

    while (currentGeneration.length > 0) {
      const nextGeneration = [];

      for (const { id: currentId, level } of currentGeneration) {
        if (level >= depth) continue;

        const [rows] = await db.promise().query(
          'SELECT vader_id, moeder_id FROM burger WHERE id_code = ?',
          [currentId]
        );

        if (rows.length === 0) continue;

        const { vader_id, moeder_id } = rows[0];

        if (vader_id) {
          ancestors.add(vader_id);
          nextGeneration.push({ id: vader_id, level: level + 1 });
        }
        if (moeder_id) {
          ancestors.add(moeder_id);
          nextGeneration.push({ id: moeder_id, level: level + 1 });
        }
      }

      currentGeneration = nextGeneration;
    }

    return ancestors;
  }

  const [ancestors1, ancestors2] = await Promise.all([
    getAncestors(id1),
    getAncestors(id2)
  ]);

  for (const ancestor of ancestors1) {
    if (ancestors2.has(ancestor)) {
      return true; // Gemeenschappelijke voorouder gevonden
    }
  }

  return false; // Geen inteelt tot 3de graad
}


app.listen(3000, () => console.log('Server draait op http://localhost:3000'));