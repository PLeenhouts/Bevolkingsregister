document.getElementById('zoekBtn').addEventListener('click', function () {
  const bsn = document.getElementById('zoekBSN').value.trim();
  const output = document.getElementById('zoekResultaat');

  // 1. Geen invoer? -> toon melding en stop
  if (!bsn) {
    output.textContent = '⚠ Geen BSN ingevuld. Vul eerst een BSN in (bijv. 01).';
    output.style.color = 'var(--pink)'; // roze waarschuwing uit je thema
    return;
  }

  // 2. Wel invoer? -> reset styling en ga ophalen
  output.style.color = ''; // terug naar standaard kleur
  output.textContent = '⏳ Bezig met ophalen...';

  fetch('api/burgers.php?id=' + encodeURIComponent(bsn))
    .then(function (response) {
      if (!response.ok) {
        throw new Error('Serverfout: ' + response.status);
      }
      return response.json();
    })
    .then(function (data) {
      if (!data) {
        output.textContent = '❌ Geen burger gevonden met BSN ' + bsn;
        output.style.color = 'var(--pink)';
        return;
      }

      // Bewaar de laatst opgehaalde burger in memory (voor "Laad in formulier")
      window._laatsteBurger = data;

      // Toon netjes het resultaat
      output.style.color = ''; // weer normaal
      output.textContent =
        'BSN: ' + (data.bsn ?? data.BSN ?? '-') + '\n' +
        'Naam: ' + (data.voornaam ?? '-') + ' ' + (data.achternaam ?? '-') + '\n' +
        'Geboortedatum: ' + (data.geboortedatum ?? '-') + '\n' +
        'Geslacht: ' + (data.geslacht ?? '-') + '\n' +
        'Adres code: ' + (data.adres_code ?? '-') + '\n' +
        'E-mail: ' + (data.e_mail ?? data.email ?? '-') + '\n' +
        'Registratiecode: ' + (data.registratiecode ?? '-') + '\n' +
        'Status: ' + (data.status ?? '-');
    })
    .catch(function (err) {
      output.textContent = '❌ Fout tijdens ophalen: ' + err.message;
      output.style.color = 'var(--pink)';
    });
});