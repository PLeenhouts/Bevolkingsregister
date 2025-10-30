let burgersArray = [];

function fetchBurgers() {
    fetch('http://localhost:3000/burgers')
        .then(response => response.json())
        .then(data => {
            burgersArray = data;
            const stamboom = bouwStamboom(1); // startpunt
            toonStamboomHTML(stamboom, document.getElementById('stamboomContainer'));
        })
        .catch(err => {
            console.error('Fout bij ophalen burgers:', err);
        });
}

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

function toonStamboomHTML(node, container) {
    if (!node) return;

    const div = document.createElement('div');
    div.style.marginLeft = `${node.generatie * 30}px`;
    div.style.padding = '5px';
    div.style.borderLeft = '2px solid #00ffff';
    div.style.color = '#00ffff';
    div.style.fontFamily = 'Orbitron, sans-serif';
    div.textContent = `👤 ${node.naam} (generatie ${node.generatie})`;
    container.appendChild(div);

    if (node.vader) {
        toonStamboomHTML(node.vader, container);
    }
    if (node.moeder) {
        toonStamboomHTML(node.moeder, container);
    }
}

fetchBurgers();