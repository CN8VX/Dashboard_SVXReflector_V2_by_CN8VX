// Fonction pour charger le contenu dynamique
function fetchCreateHtml() {
    fetch('create_html.php')
        .then(response => {
            if (!response.ok) {
                throw new Error('Erreur lors du chargement du fichier.');
            }
            return response.text();
        })
        .then(data => {
            const createHtmlElement = document.getElementById("create_html");
            if (createHtmlElement) {
                createHtmlElement.innerHTML = data;
            }
        })
        .catch(error => console.error('Erreur : ', error));
}

// APPLIQUER LE THÈME IMMÉDIATEMENT - AVANT TOUT
const savedTheme = localStorage.getItem('svx-theme') || 'light';
if (document.documentElement) {
    document.documentElement.setAttribute('data-theme', savedTheme);
} else {
    // Si document.documentElement n'existe pas encore, utiliser document.querySelector
    const html = document.querySelector('html');
    if (html) {
        html.setAttribute('data-theme', savedTheme);
    }
}

// Configuration
let refreshInterval;
let isRefreshing = false;

function initTheme() {
    // Réappliquer le thème sauvegardé
    const savedTheme = localStorage.getItem('svx-theme') || 'light';
    document.documentElement.setAttribute('data-theme', savedTheme);
    console.log('Theme reapplied:', savedTheme);
    
    const themeToggle = document.getElementById('themeToggle');
    if (themeToggle) {
        // Nettoyer les anciens écouteurs
        themeToggle.onclick = null;
        themeToggle.removeAttribute('onclick');
        
        // Ajouter le nouvel écouteur
        themeToggle.addEventListener('click', function(e) {
            e.preventDefault();
            toggleTheme();
        });
        console.log('Theme toggle reinitialized');
    } else {
        console.error('Theme toggle element not found');
    }
}

function toggleTheme() {
    const currentTheme = document.documentElement.getAttribute('data-theme') || 'light';
    const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
    
    console.log('Toggling theme from', currentTheme, 'to', newTheme);
    
    // Appliquer immédiatement le nouveau thème
    document.documentElement.setAttribute('data-theme', newTheme);
    localStorage.setItem('svx-theme', newTheme);
    
    console.log('Theme successfully changed to:', newTheme);
    console.log('LocalStorage value:', localStorage.getItem('svx-theme'));
    
    //showRefreshIndicator('Theme changed to ' + (newTheme === 'dark' ? 'Dark' : 'Light'));
}

// Appel initial et rafraîchissement seulement si l'élément create_html existe
document.addEventListener('DOMContentLoaded', () => {
    if (document.getElementById("create_html")) {
        fetchCreateHtml();
        setInterval(fetchCreateHtml, 1000);
    }
});