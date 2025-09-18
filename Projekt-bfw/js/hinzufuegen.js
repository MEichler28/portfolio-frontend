
document.getElementById("hinzufuegen").addEventListener("click", () => {
    // Daten sammeln
    const name = document.getElementById("name").value;
    const preis = document.getElementById("preis").value;
    const hersteller = document.getElementById("hersteller").value === "Neu"
        ? document.getElementById("neuerHersteller").value
        : document.getElementById("hersteller").value;
    const zustandSelect = document.getElementById("zustand");
    const vZustand = document.getElementById("v-zustand");


    // Später hier AJAX/FETCH zur Datenbank einbauen
    console.log("Sneaker hinzufügen:", { name, preis, hersteller });
});

// Elemente holen
// Elemente holen
const nameInput = document.getElementById("name");
const preisInput = document.getElementById("preis");
const kaufdatumInput = document.getElementById("kaufdatum");
const herstellerSelect = document.getElementById("hersteller");
const neuerHerstellerInput = document.getElementById("neuerHersteller");
const bildInput = document.getElementById("bild");
const zustandSelect = document.getElementById("zustand");      // <-- Hier Zustand holen

const vName = document.getElementById("v-name");
const vPreis = document.getElementById("v-preis");
const vKaufdatum = document.getElementById("v-kaufdatum");
const vHersteller = document.getElementById("v-hersteller");
const vBild = document.getElementById("v-bild");
const vZustand = document.getElementById("v-zustand");         // <-- Vorschau Zustand holen

function updateVorschau() {
  const name = nameInput.value.trim() || "Name";
  const preis = preisInput.value.trim() ? `Preis: ${preisInput.value} €` : "Preis: - €";
  const kaufdatum = kaufdatumInput.value ? `Kaufdatum: ${kaufdatumInput.value}` : "Kaufdatum: -";

  let hersteller = "";
  if (herstellerSelect.value === "Neu") {
    hersteller = neuerHerstellerInput.value.trim() || "-";
  } else {
    hersteller = herstellerSelect.value || "-";
  }
  hersteller = `Hersteller: ${hersteller}`;

  let zustand = zustandSelect.value || "-";    // Hier funktioniert es jetzt
  zustand = `Zustand: ${zustand}`;

  if (bildInput.files && bildInput.files[0]) {
    const reader = new FileReader();
    reader.onload = function(e) {
      vBild.src = e.target.result;
      vBild.style.display = "block";
    };
    reader.readAsDataURL(bildInput.files[0]);
  } else {
    vBild.src = "";
    vBild.style.display = "none";
  }

  vName.textContent = name;
  vPreis.textContent = preis;
  vKaufdatum.textContent = kaufdatum;
  vHersteller.textContent = hersteller;
  vZustand.textContent = zustand;  // Zustand hinzufügen
}

// Event-Listener wie gehabt...

nameInput.addEventListener("input", updateVorschau);
preisInput.addEventListener("input", updateVorschau);
kaufdatumInput.addEventListener("input", updateVorschau);
herstellerSelect.addEventListener("change", () => {
  neuerHerstellerInput.style.display = herstellerSelect.value === "Neu" ? "block" : "none";
  updateVorschau();
});
neuerHerstellerInput.addEventListener("input", updateVorschau);
zustandSelect.addEventListener("change", updateVorschau);
bildInput.addEventListener("change", updateVorschau);

document.getElementById("hinzufuegen").addEventListener("click", () => {
  const name = nameInput.value;
  const preis = preisInput.value;
  const kaufdatum = kaufdatumInput.value;
  const hersteller = herstellerSelect.value === "Neu"
    ? neuerHerstellerInput.value
    : herstellerSelect.value;
  const zustand = zustandSelect.value;

  console.log("Sneaker hinzufügen:", { name, preis, kaufdatum, hersteller });
  alert("Sneaker hinzugefügt!");
});
