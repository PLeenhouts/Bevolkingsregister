<?php
// index.php
session_start();

// sessievariabelen klaarzetten
$isIngelogd = isset($_SESSION['user_id']);
$naam   = $isIngelogd ? $_SESSION['user_naam']   : null;
$rol    = $isIngelogd ? $_SESSION['user_rol']    : null;
$email  = $isIngelogd ? $_SESSION['user_email']  : null;
?>
<!DOCTYPE html>
<html lang="nl">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Springfield Space Register 🛰️</title>
  <link href="style.css" rel="stylesheet" type="text/css" />
</head>
<body>

<header>
  <div class="app-title">
    <div class="title-main">🌞 Springfield Space Register</div>
    <div class="title-sub">Burgerregistratie • Burgerkoppeling • Mutaties</div>
  </div>

  <?php if (!$isIngelogd): ?>
    <!-- LOGIN FORM -->
    <form class="login-panel" id="loginForm" method="POST" action="login.php">
      <div class="field-group">
        <label for="loginGebruiker">Gebruiker (e-mail)</label>
        <input type="text" id="loginGebruiker" name="email" placeholder="bouvier.s@springfield.gov" required>
      </div>

      <div class="field-group">
        <label for="loginWachtwoord">Wachtwoord</label>
        <input type="password" id="loginWachtwoord" name="wachtwoord" placeholder="••••••••" required>
      </div>

      <button class="btn" type="submit" id="loginBtn">Login</button>

      <div class="login-status" id="loginStatus">Niet ingelogd</div>
    </form>
  <?php else: ?>
    <!-- USER INFO + LOGOUT -->
    <div class="login-panel">
      <div class="user-info">
        <div class="user-name">👋 Welkom, <?= htmlspecialchars($naam) ?></div>
        <div class="user-meta">
          <?= htmlspecialchars($email) ?><br>
          <span class="role-chip"><?= htmlspecialchars($rol) ?></span>
        </div>
      </div>
      <form method="POST" action="logout.php">
        <button class="btn btn-logout" type="submit">Logout</button>
      </form>
    </div>
  <?php endif; ?>
</header>

<main>
  <!-- PANEEL 1: BURGER ZOEKEN -->
  <section class="panel" id="paneelZoeken">
    <div class="panel-header">
      <div class="panel-title">
        👽 Burger opvragen <span class="badge">Query</span>
      </div>
    </div>

    <?php if (!$isIngelogd): ?>
      <div class="auth-warning">⚠️ Je moet eerst inloggen om burgerdata te zien.</div>
    <?php else: ?>
      <form class="zoek-form" id="zoekForm" onsubmit="return false;">
        <div class="field-group">
          <label for="zoekBurgerId">Burger ID</label>
          <input type="text" id="zoekBurgerId" placeholder="bijv. 10">
        </div>
        <button class="btn" type="button" id="zoekBtn">Zoeken</button>
      </form>

      <div class="section-label">Resultaat (alleen-lezen)</div>
      <div class="zoek-resultaat" id="zoekResultaat">
        -- geen resultaat --
      </div>

      <button class="btn btn-edit-start" id="startEditBtn" type="button" disabled>Wijzigen…</button>
      <div class="mini-hint" id="editHint">Zoek eerst een burger. Daarna kun je wijzigen.</div>
    <?php endif; ?>
  </section>

  <!-- PANEEL 2: BEHEER / ROL INFO -->
  <section class="panel" id="paneelBewerkenInfo">
    <div class="panel-header">
      <div class="panel-title">
        🛠 Beheer <span class="badge">Mutatie</span>
      </div>
    </div>

    <?php if (!$isIngelogd): ?>
      <div class="auth-warning">🔒 Niet ingelogd. Geen toegang.</div>
    <?php else: ?>
      <?php if ($rol === 'admin'): ?>
        <div class="admin-msg">
          Welkom, beheerder. Jij hebt volledige rechten om burgers te wijzigen.
        </div>
      <?php else: ?>
        <div class="user-msg">
          Je bent ingelogd. Je mag burgers aanpassen.
        </div>
      <?php endif; ?>
    <?php endif; ?>
  </section>

  <!-- PANEEL 3: BURGER AANPASSEN -->
  <section class="panel" id="paneelEdit">
    <div class="panel-header">
      <div class="panel-title">
        ✏️ Burger aanpassen <span class="badge">Edit</span>
      </div>
    </div>

    <?php if (!$isIngelogd): ?>
      <div class="auth-warning">🔒 Niet ingelogd. Kan geen gegevens bewerken.</div>
    <?php else: ?>
      <form id="editForm" onsubmit="return false;">
        <div class="section-label">Burgergegevens</div>

        <div class="edit-grid">

          <div class="field-group">
            <label for="editBurger_id">Burger ID</label>
            <input type="text" id="editBurger_id" name="Burger_id" readonly>
          </div>

          <div class="field-group">
            <label for="editVoornaam">Voornaam</label>
            <input type="text" id="editVoornaam" name="Voornaam" disabled>
          </div>

          <div class="field-group">
            <label for="editAchternaam">Achternaam</label>
            <input type="text" id="editAchternaam" name="Achternaam" disabled>
          </div>

          <div class="field-group">
            <label for="editGeboortedatum">Geboortedatum</label>
            <input type="date" id="editGeboortedatum" name="Geboortedatum" disabled>
          </div>

          <div class="field-group">
            <label for="editDNA">DNA code</label>
            <input type="text" id="editDNA" name="DNA_code" disabled>
          </div>

          <div class="field-group">
            <label for="editFamilie">Familie code</label>
            <input type="text" id="editFamilie" name="Familie_code" disabled>
          </div>

        </div>

        <!-- Let op: we tonen huwelijken / kinderen nu niet als editbare velden,
             omdat de DB-structuur die niet direct per burger 1-op-1 mapt.
             Die kunnen later in aparte panelen. -->

        <button class="btn btn-save" id="saveBtn" type="button" disabled>Opslaan</button>
        <div class="save-status" id="saveStatus"></div>
      </form>
    <?php endif; ?>
  </section>
</main>

<?php if ($isIngelogd): ?>
<script>
// ====== STATE ======
let _laatsteBurger = null;   // data van de laatst opgehaalde bestaande burger
let _saveMode = "idle";      // "idle" | "edit" | "new"

const zoekBtn       = document.getElementById('zoekBtn');
const startEditBtn  = document.getElementById('startEditBtn');
const newBurgerBtn  = document.getElementById('newBurgerBtn');
const saveBtn       = document.getElementById('saveBtn');
const statusEl      = document.getElementById('saveStatus');
const out           = document.getElementById('zoekResultaat');
const hintEl        = document.getElementById('editHint');

const editableIds = [
  'editVoornaam',
  'editAchternaam',
  'editGeboortedatum',
  'editDNA',
  'editFamilie'
];

function setEditEnabled(enabled) {
  editableIds.forEach(id => {
    const el = document.getElementById(id);
    if (el) el.disabled = !enabled;
  });
  saveBtn.disabled = !enabled;
}

// form helpers
function clearForm() {
  document.getElementById('editBurger_id').value     = '';
  document.getElementById('editVoornaam').value      = '';
  document.getElementById('editAchternaam').value    = '';
  document.getElementById('editGeboortedatum').value = '';
  document.getElementById('editDNA').value           = '';
  document.getElementById('editFamilie').value       = '';
}

function fillFormFromData(data) {
  document.getElementById('editBurger_id').value     = data.Burger_id ?? '';
  document.getElementById('editVoornaam').value      = data.Voornaam ?? '';
  document.getElementById('editAchternaam').value    = data.Achternaam ?? '';
  document.getElementById('editGeboortedatum').value = data.Geboortedatum ?? '';
  document.getElementById('editDNA').value           = data.DNA_code ?? '';
  document.getElementById('editFamilie').value       = data.Familie_code ?? '';
}

// ====== ZOEKEN (bestaande burger ophalen) ======
zoekBtn.addEventListener('click', function () {
  const burgerId = document.getElementById('zoekBurgerId').value.trim();

  // reset state
  _saveMode = "idle";
  _laatsteBurger = null;
  setEditEnabled(false);
  statusEl.textContent = '';

  if (!burgerId) {
    out.textContent = '⚠ Geen Burger ID ingevuld.';
    out.style.color = 'var(--pink)';
    startEditBtn.disabled = true;
    hintEl.textContent = 'Zoek eerst een burger. Of voeg iemand nieuw toe.';
    return;
  }

  out.style.color = '';
  out.textContent = '⏳ Ophalen...';

  startEditBtn.disabled = true;
  hintEl.textContent = 'Bezig met ophalen...';

  fetch('api/burgers.php?id=' + encodeURIComponent(burgerId))
    .then(r => {
      if (!r.ok) throw new Error('Serverfout ' + r.status);
      return r.json();
    })
    .then(data => {
      if (!data || data.success === false) {
        out.textContent = data && data.error
          ? '❌ ' + data.error
          : ('❌ Geen burger gevonden met ID ' + burgerId);
        out.style.color = 'var(--pink)';
        startEditBtn.disabled = true;
        hintEl.textContent = 'Zoek eerst een burger. Of voeg iemand nieuw toe.';
        _laatsteBurger = null;
        _saveMode = "idle";
        return;
      }

      // We hebben een geldige burger
      _laatsteBurger = data;

      out.style.color = '';
      out.textContent =
        'ID: ' + (data.Burger_id ?? '-') + '\n' +
        'Naam: ' + (data.Voornaam ?? '-') + ' ' + (data.Achternaam ?? '-') + '\n' +
        'Geboortedatum: ' + (data.Geboortedatum ?? '-') + '\n' +
        'DNA: ' + (data.DNA_code ?? '-') + '\n' +
        'Familiecode: ' + (data.Familie_code ?? '-');

      // Vul formulier (nog gelockt)
      fillFormFromData(data);

      // Nu mag de gebruiker "Wijzigen…" kiezen
      startEditBtn.disabled = false;
      hintEl.textContent = 'Klik op "Wijzigen…" om deze burger aan te passen.';
      _saveMode = "idle"; // nog niet aan het bewerken!
    })
    .catch(err => {
      out.textContent = '❌ Fout tijdens ophalen: ' + err.message;
      out.style.color = 'var(--pink)';
      startEditBtn.disabled = true;
      hintEl.textContent = 'Zoek eerst een burger. Of voeg iemand nieuw toe.';
      _laatsteBurger = null;
      _saveMode = "idle";
    });
});

// ====== BESTAANDE BURGER BEWERKEN ======
startEditBtn.addEventListener('click', function () {
  if (!_laatsteBurger) {
    hintEl.textContent = 'Geen burger geladen om te bewerken.';
    return;
  }
  // nu gaan we een bestaande burger wijzigen
  _saveMode = "edit";

  setEditEnabled(true);
  hintEl.textContent = 'Je bewerkt nu een bestaande burger. Vergeet niet op Opslaan te klikken.';
});

// ====== NIEUWE BURGER MAKEN ======
newBurgerBtn.addEventListener('click', function () {
  // nieuwe modus
  _saveMode = "new";
  _laatsteBurger = null;

  // maak formulier leeg en unlock
  clearForm();
  setEditEnabled(true);
  // Burger_id blijft leeg, wordt door DB bepaald

  out.style.color = '';
  out.textContent =
    "Nieuwe burger aanmaken\n" +
    "Vul de velden hieronder in en klik op Opslaan.\n" +
    "(Burger ID wordt automatisch toegekend.)";

  startEditBtn.disabled = true; // je kan geen 'Wijzigen…' doen op iets dat nog niet bestaat
  hintEl.textContent = 'Je bent nu een nieuwe burger aan het invoeren.';
  statusEl.textContent = '';
});

// ====== OPSLAAN (kan UPDATE of INSERT zijn afhankelijk van _saveMode) ======
saveBtn.addEventListener('click', function () {
  // safety check
  if (_saveMode !== "edit" && _saveMode !== "new") {
    statusEl.textContent = '❌ Kan niet opslaan: geen modus (new/edit) actief.';
    statusEl.style.color = 'var(--pink)';
    return;
  }

  statusEl.textContent = '💾 Opslaan...';
  statusEl.style.color = '';

  const payload = {
    Burger_id:     document.getElementById('editBurger_id').value.trim(),
    Voornaam:      document.getElementById('editVoornaam').value.trim(),
    Achternaam:    document.getElementById('editAchternaam').value.trim(),
    Geboortedatum: document.getElementById('editGeboortedatum').value.trim(),
    DNA_code:      document.getElementById('editDNA').value.trim(),
    Familie_code:  document.getElementById('editFamilie').value.trim(),
    mode:          _saveMode        // <-- heel belangrijk
  };

  fetch('api/save_burger.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(payload)
  })
    .then(r => r.json())
    .then(res => {
      if (!res.success) {
        let msg = '❌ Opslaan mislukt: ' + (res.error || 'onbekende fout');
        if (res.details) {
          msg += ' (' + res.details + ')';
        }
        statusEl.textContent = msg;
        statusEl.style.color = 'var(--pink)';
        return;
      }

      // succes
      if (res.nieuwe_id) {
        // DB heeft een nieuwe rij gemaakt, toon dat ID meteen in het formulier
        document.getElementById('editBurger_id').value = res.nieuwe_id;
      }

      statusEl.textContent = '✅ Opgeslagen!';
      statusEl.style.color = 'green';

      // lock velden weer dicht
      setEditEnabled(false);

      // we zijn klaar met de actie → terug naar idle
      _saveMode = "idle";
      hintEl.textContent = 'Wijzigingen opgeslagen. Je kunt opnieuw zoeken of een nieuwe burger maken.';
    })
    .catch(err => {
      statusEl.textContent = '❌ Opslaan mislukt: ' + err.message;
      statusEl.style.color = 'var(--pink)';
    });
});
</script>
<?php endif; ?>


</body>
</html>
