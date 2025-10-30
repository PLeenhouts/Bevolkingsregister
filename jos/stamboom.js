let burgersArray = []; // Globale array met alle burgers

// Haal burgers op en start stamboomanalyse
function fetchBurgers() {
    fetch('http://localhost:3000/burgers')
        .then(response => response.json())
        .then(data => {
            burgersArray = data;
            console.log('✅ Burgers ontvangen:', burgersArray);

            // Start met burger ID 3 (pas aan naar wens)
            const stamboom = bouwStamboom(1);
            toonStamboom(stamboom);
        })
        .catch(err => {
            console.error('❌ Fout bij ophalen burgers:', err);
        });
}

// 🔁 Recursieve functie op basis van ID's
function bouwStamboom(burgerId, generatie = 0) {
    const burger = burgersArray.find(b => b.id === burgerId);
    if (!burger) return null;

    const vader = burgersArray.find(b => b.id === burger.vader);
    const moeder = burgersArray.find(b => b.id === burger.moeder);

    return {
        naam: burger.naam,
        generatie,
        vader: vader ? bouwStamboom(vader.id, generatie + 1) : null,
        moeder: moeder ? bouwStamboom(moeder.id, generatie + 1) : null
    };
}

// 🖨️ Toon stamboom in console
function toonStamboom(node, indent = '') {
    if (!node) return;

    console.log(`${indent}👤 ${node.naam} (generatie ${node.generatie})`);
    if (node.vader || node.moeder) {
        if (node.vader) {
            console.log(`${indent}↳ Vader:`);
            toonStamboom(node.vader, indent + '   ');
        }
        if (node.moeder) {
            console.log(`${indent}↳ Moeder:`);
            toonStamboom(node.moeder, indent + '   ');
        }
    }
}

// Start bij laden
fetchBurgers();