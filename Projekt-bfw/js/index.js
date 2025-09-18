/*----------------------------------Hauptindex------------------------------------------------*/
document.addEventListener('DOMContentLoaded', () => {
  const urlParams = new URLSearchParams(window.location.search);
  if (urlParams.has('deleted')) {
    const popup = document.getElementById('popup-info');
    if (popup) {
      popup.style.display = 'block';
    }
    // URL bereinigen, damit es nur einmal angezeigt wird
    window.history.replaceState({}, document.title, window.location.pathname);
  }

  const filter = document.getElementById('filter');
  const sucheInput = document.getElementById('suche');
  const anzahlSelect = document.getElementById('anzahl');
  const markenContainer = document.querySelector('.content');
  const paginationDiv = document.getElementById('pagination');

  // Alle Markenblöcke und Items sammeln
  const markenBlocks = Array.from(document.querySelectorAll('section.markenblock'));
  const flacheListeId = 'flache-liste';
  let currentPage = 1;

  // Hilfsfunktion: Alle Items flach sammeln (aus Markenblöcken, ohne Verschieben!)
  function getAllItemsFlat() {
    let items = [];
    markenBlocks.forEach(block => {
      items.push(...block.querySelectorAll('.item'));
    });
    return items;
  }

  // Funktion: Anzeige aktualisieren
  function updateAnzeige() {
    const filterValue = filter.value.toLowerCase();
    const sucheText = sucheInput.value.toLowerCase();
    const anzahlProSeite = parseInt(anzahlSelect.value);

    // Items filtern nach Suchbegriff
    let gefilterteItems = getAllItemsFlat().filter(item => {
      const modell = item.querySelector('h3').textContent.toLowerCase();
      return modell.includes(sucheText);
    });

    // Flache Liste (für Preis-Sortierung) vorbereiten
    let flacheListe = document.getElementById(flacheListeId);
    if (!flacheListe) {
      flacheListe = document.createElement('div');
      flacheListe.id = flacheListeId;
      markenContainer.appendChild(flacheListe);
    }

    if (filterValue === 'preis-auf' || filterValue === 'preis-ab') {
  // Markenblöcke ausblenden (lassen die Items drin!)
  markenBlocks.forEach(block => {
    block.style.display = 'none';
  });

  // Flache Liste zeigen und leeren
  flacheListe.style.display = 'flex';
  flacheListe.classList.add('grid');
  flacheListe.innerHTML = '';

  // Items nach Preis sortieren
  gefilterteItems.sort((a, b) => {
    const preisA = parseFloat(a.getAttribute('data-preis')) || 0;
    const preisB = parseFloat(b.getAttribute('data-preis')) || 0;
    return filterValue === 'preis-auf' ? preisA - preisB : preisB - preisA;
  });

  // Pagination berechnen
  const startIndex = (currentPage - 1) * anzahlProSeite;
  const endIndex = anzahlProSeite === 999 ? gefilterteItems.length : startIndex + anzahlProSeite;

  // Items für aktuelle Seite auswählen
  const itemsToShow = gefilterteItems.slice(startIndex, endIndex);

  // Klone der Items in die flache Liste einfügen (Originale bleiben in den Markenblöcken)
  itemsToShow.forEach(item => {
    const clone = item.cloneNode(true);
    clone.style.display = 'inline-block';
    flacheListe.appendChild(clone);
  });

  updatePagination(gefilterteItems.length, anzahlProSeite);

} else {
  // 'alle' oder markenbasierte Filter

  // Flache Liste ausblenden und leeren
  flacheListe.style.display = 'none';
  flacheListe.classList.remove('grid');
  flacheListe.innerHTML = '';

  // Markenblöcke filtern und Items ein-/ausblenden
  markenBlocks.forEach(block => {
    const marke = block.getAttribute('data-marke').toLowerCase();
    const itemsImBlock = Array.from(block.querySelectorAll('.item'));
    let hatSichtbareItems = false;

    itemsImBlock.forEach(item => {
      const modell = item.querySelector('h3').textContent.toLowerCase();
      const passtSuche = modell.includes(sucheText);
      const passtMarke = (filterValue === 'alle' || filterValue === marke);

      if (passtSuche && passtMarke) {
        item.style.display = 'block';
        hatSichtbareItems = true;
      } else {
        item.style.display = 'none';
      }
    });

    block.style.display = hatSichtbareItems ? 'block' : 'none';
  });

  // Pagination für sichtbare Items in Markenblöcken berechnen
  const sichtbareItems = getAllItemsFlat().filter(item => {
    const parentSection = item.closest('section.markenblock');
    return parentSection && parentSection.style.display !== 'none' && item.style.display !== 'none';
  });

  const startIndex = (currentPage - 1) * anzahlProSeite;
  const endIndex = anzahlProSeite === 999 ? sichtbareItems.length : startIndex + anzahlProSeite;

  sichtbareItems.forEach((item, i) => {
    item.style.display = (i >= startIndex && i < endIndex) ? 'block' : 'none';
  });

  updatePagination(sichtbareItems.length, anzahlProSeite);

  markenBlocks.forEach(block => {
  const items = Array.from(block.querySelectorAll('.item'));
  const sichtbareItemsInBlock = items.some(item => item.style.display !== 'none');
  block.style.display = sichtbareItemsInBlock ? 'block' : 'none';
});

}

  }

  // Funktion: Pagination aktualisieren
  function updatePagination(totalItems, itemsPerPage) {
    paginationDiv.innerHTML = '';

    if (itemsPerPage === 999 || totalItems <= itemsPerPage) {
      return; // Keine Pagination nötig
    }

    const totalPages = Math.ceil(totalItems / itemsPerPage);

    for (let i = 1; i <= totalPages; i++) {
      const btn = document.createElement('button');
      btn.textContent = i;
      if (i === currentPage) {
        btn.disabled = true;
      }
      btn.addEventListener('click', () => {
        currentPage = i;
        updateAnzeige();
        window.scrollTo(0, 0); // Scroll oben
      });
      paginationDiv.appendChild(btn);
    }
  }

  // Event-Listener für Filter, Suche und Anzahl
  filter.addEventListener('change', () => {
    currentPage = 1;
    updateAnzeige();
  });

  sucheInput.addEventListener('input', () => {
    currentPage = 1;
    updateAnzeige();
  });

  anzahlSelect.addEventListener('change', () => {
    currentPage = 1;
    updateAnzeige();
  });

  // Initial anzeigen
  updateAnzeige();
});

function closePopup() {
  const popup = document.getElementById("popup-info");
  if (popup) popup.style.display = "none";
}

document.addEventListener("DOMContentLoaded", () => {
  const urlParams = new URLSearchParams(window.location.search);
  if (urlParams.get("deleted") === "1") {
    const popup = document.getElementById("popup-info");
    if (popup) popup.style.display = "block";

    // URL ohne Parameter sauber zurücksetzen (optional)
    window.history.replaceState({}, document.title, window.location.pathname);
  }
});
