document.getElementById('burgerForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const naam = document.getElementById('naam').value;
    const vader = document.getElementById('vader').value;
    const moeder = document.getElementById('moeder').value;

    fetch('http://localhost:3000/inteelt', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ naam, vader, moeder })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Burger toegevoegd!');
            fetchBurgers(); // Vernieuw de lijst met burgers
        } else {
            alert('Er is iets misgegaan.');
        }
    });
});

// Functie om burgers op te halen
function fetchBurgers() {
    fetch('http://localhost:3000/burgers')
    .then(response => response.json())
    .then(data => {
        const burgerLijst = document.getElementById('burgerLijst');
        burgerLijst.innerHTML = '';
        data.forEach(burger => {
            const burgerDiv = document.createElement('div');
            burgerDiv.textContent = `${burger.id} -${burger.naam} - ${burger.vader} - ${burger.moeder}`;
            burgerLijst.appendChild(burgerDiv);
        });
    });
}

// burger opzoeken op Id

function zoekBurger() {
    // const id = document.getElementById('zoekId').value;
    const id = parseInt(document.getElementById('zoekId').value); //zeker een integer
    if (isNaN(id)) return alert('Voer een geldig ID in'); //controleer of het een getal is
    if (!id) return alert('Voer een ID in');

    
    fetch(`http://localhost:3000/burgers/${id}`)
        .then(response => {
            if (!response.ok) throw new Error('Burger niet gevonden');
            return response.json();
        })
        .then(burger => {
            const gevondenDiv = document.getElementById('gevondenBurger');
            gevondenDiv.textContent = `${burger.id} - ${burger.naam} - ${burger.vader} - ${burger.moeder}`;
        })
        .catch(error => {
            document.getElementById('gevondenBurger').textContent = 'Geen burger gevonden.';
            console.error(error);
        });
}


// Functie om een burger te updaten
function updateBurger() {
    const updateId = parseInt(document.getElementById('updateId').value);
    const updateNaam = document.getElementById('updateNaam').value;
    const updateVader = document.getElementById('updateVader').value;
    const updateMoeder = document.getElementById('updateMoeder').value;

    fetch(`http://localhost:3000/burgers/${updateId}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            naam: updateNaam,
            vader: updateVader,
            moeder: updateMoeder
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Burger bijgewerkt!');
            fetchBurgers();
        } else {
            alert('Er is iets misgegaan.');
        }
    });
}
/*
// Functie om een student te verwijderen
function deleteStudent(id) {
    fetch(`http://localhost:3000/studenten/${id}`, {
        method: 'DELETE'
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Student verwijderd!');
            fetchStudenten();
        } else {
            alert('Er is iets misgegaan.');
        }
    });
}
*/
// Initialiseer de lijst met studenten
fetchBurgers();
