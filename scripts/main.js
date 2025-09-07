// ===============================
// CONFIG DEBUG
// ===============================
const DEBUG = false; // Mets true pour voir les logs en console

function debugLog(...args) {
    if (DEBUG) console.log(...args);
}
function debugError(...args) {
    if (DEBUG) console.error(...args);
}

// ===============================
// APPLIQUER LE THÈME IMMÉDIATEMENT - AVANT TOUT
// ===============================
const savedTheme = localStorage.getItem('svx-theme') || 'light';
if (document.documentElement) {
    document.documentElement.setAttribute('data-theme', savedTheme);
} else {
    const html = document.querySelector('html');
    if (html) {
        html.setAttribute('data-theme', savedTheme);
    }
}

// Configuration
let refreshInterval;
let isRefreshing = false;

function initTheme() {
    const savedTheme = localStorage.getItem('svx-theme') || 'light';
    document.documentElement.setAttribute('data-theme', savedTheme);
    debugLog('Theme reapplied:', savedTheme);
    
    const themeToggle = document.getElementById('themeToggle');
    if (themeToggle) {
        themeToggle.onclick = null;
        themeToggle.removeAttribute('onclick');
        
        themeToggle.addEventListener('click', function(e) {
            e.preventDefault();
            toggleTheme();
        });
        debugLog('Theme toggle reinitialized');
    } else {
        debugError('Theme toggle element not found');
    }
}

function toggleTheme() {
    const currentTheme = document.documentElement.getAttribute('data-theme') || 'light';
    const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
    
    debugLog('Toggling theme from', currentTheme, 'to', newTheme);
    
    document.documentElement.setAttribute('data-theme', newTheme);
    localStorage.setItem('svx-theme', newTheme);
    
    debugLog('Theme successfully changed to:', newTheme);
    debugLog('LocalStorage value:', localStorage.getItem('svx-theme'));
}

// Auto-refresh functionality
function startAutoRefresh() {
    stopAutoRefresh();
    const refreshTime = parseInt(document.body.getAttribute('data-refresh-interval')) * 1000 || 5000;
    
    refreshInterval = setInterval(() => {
        if (!isRefreshing) {
            refreshTable();
        }
    }, refreshTime);
}

function stopAutoRefresh() {
    if (refreshInterval) {
        clearInterval(refreshInterval);
        refreshInterval = null;
    }
}

function refreshTable() {
    if (isRefreshing) return;
    isRefreshing = true;
    
    const currentUrl = new URL(window.location.href);
    currentUrl.searchParams.set('ajax', '1');
    
    fetch(currentUrl.toString())
        .then(response => response.text())
        .then(html => {
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            
            const currentTbody = document.querySelector('.log-tablelgvwr tbody');
            const newTbody = doc.querySelector('.log-tablelgvwr tbody');
            if (currentTbody && newTbody) {
                currentTbody.innerHTML = newTbody.innerHTML;
            }
            
            const newEntriesCount = doc.querySelector('#entriesCount');
            const currentEntriesCount = document.querySelector('#entriesCount');
            if (newEntriesCount && currentEntriesCount) {
                currentEntriesCount.textContent = newEntriesCount.textContent;
            }
            
            const currentPagination = document.querySelector('.pagination-container');
            const newPagination = doc.querySelector('.pagination-container');
            if (currentPagination && newPagination) {
                currentPagination.innerHTML = newPagination.innerHTML;
            } else if (newPagination && !currentPagination) {
                document.querySelector('.container').appendChild(newPagination);
            } else if (!newPagination && currentPagination) {
                currentPagination.remove();
            }
        })
        .catch(error => {
            debugError('Erreur lors du rafraîchissement:', error);
        })
        .finally(() => {
            isRefreshing = false;
        });
}

// Search functionality
function clearSearch() {
    const form = document.getElementById('searchForm');
    if (form) {
        const inputs = form.querySelectorAll('input, select');
        inputs.forEach(input => {
            if (input.type === 'text' || input.type === 'search') {
                input.value = '';
            } else if (input.tagName === 'SELECT') {
                input.selectedIndex = 0;
            }
        });
        form.submit();
    }
}

// Refresh indicator
function showRefreshIndicator(message, type = 'success') {
    const existingIndicator = document.querySelector('.refresh-indicator');
    if (existingIndicator) existingIndicator.remove();
    
    const indicator = document.createElement('div');
    indicator.className = 'refresh-indicator';
    indicator.textContent = message;
    if (type === 'error') {
        indicator.style.backgroundColor = 'var(--danger-color)';
    }
    
    document.body.appendChild(indicator);
    
    setTimeout(() => {
        indicator.classList.add('show');
    }, 100);
    
    setTimeout(() => {
        indicator.classList.remove('show');
        setTimeout(() => {
            if (indicator.parentNode) {
                indicator.parentNode.removeChild(indicator);
            }
        }, 300);
    }, 3000);
}

// Real-time search (only for select dropdown)
function setupRealTimeSearch() {
    const actionSelect = document.querySelector('select[name="action"]');
    if (actionSelect) {
        actionSelect.removeEventListener('change', handleActionChange);
        actionSelect.addEventListener('change', handleActionChange);
    }
}
function handleActionChange() {
    document.getElementById('searchForm').submit();
}

// Table interactions
function setupTableInteractions() {
    document.removeEventListener('click', handleTableClick);
    document.addEventListener('click', handleTableClick);
}
function handleTableClick(e) {
    const row = e.target.closest('.log-row');
    if (row && !e.target.closest('a')) {
        row.classList.toggle('selected');
    }
}

// Keyboard shortcuts
function setupKeyboardShortcuts() {
    document.removeEventListener('keydown', handleKeyboardShortcuts);
    document.addEventListener('keydown', handleKeyboardShortcuts);
}
function handleKeyboardShortcuts(e) {
    if ((e.ctrlKey && e.key === 'r') || e.key === 'F5') {
        e.preventDefault();
        refreshTable();
    }
    if (e.ctrlKey && e.key === 'f') {
        e.preventDefault();
        const firstSearchInput = document.querySelector('.search-input');
        if (firstSearchInput) firstSearchInput.focus();
    }
    if (e.key === 'Escape') {
        document.querySelectorAll('.log-row.selected').forEach(row => {
            row.classList.remove('selected');
        });
    }
    if (e.ctrlKey && e.key === 'd') {
        e.preventDefault();
        toggleTheme();
    }
}

// Page visibility
function setupVisibilityHandler() {
    document.removeEventListener('visibilitychange', handleVisibilityChange);
    document.addEventListener('visibilitychange', handleVisibilityChange);
}
function handleVisibilityChange() {
    if (document.hidden) {
        stopAutoRefresh();
    } else {
        startAutoRefresh();
    }
}

// Connection monitoring
function monitorConnection() {
    window.removeEventListener('online', handleOnline);
    window.removeEventListener('offline', handleOffline);
    
    window.addEventListener('online', handleOnline);
    window.addEventListener('offline', handleOffline);
}
function handleOnline() {
    debugLog('Connexion rétablie');
    startAutoRefresh();
}
function handleOffline() {
    debugLog('Connexion perdue');
    stopAutoRefresh();
}

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    debugLog('DOM loaded, current theme:', document.documentElement.getAttribute('data-theme'));
    initTheme();
    startAutoRefresh();
    setupRealTimeSearch();
    setupTableInteractions();
    setupKeyboardShortcuts();
    setupVisibilityHandler();
    monitorConnection();
});

// Ensure theme applied once more
document.addEventListener('DOMContentLoaded', function() {
    const savedTheme = localStorage.getItem('svx-theme') || 'light';
    document.documentElement.setAttribute('data-theme', savedTheme);
    debugLog('Theme forced on DOMContentLoaded:', savedTheme);
});

// Already loaded
if (document.readyState !== 'loading') {
    debugLog('Document already loaded, initializing immediately');
    const savedTheme = localStorage.getItem('svx-theme') || 'light';
    document.documentElement.setAttribute('data-theme', savedTheme);
    setTimeout(function() {
        initTheme();
        startAutoRefresh();
        setupRealTimeSearch();
        setupTableInteractions();
        setupKeyboardShortcuts();
        setupVisibilityHandler();
        monitorConnection();
    }, 100);
}

// Clean up
window.addEventListener('beforeunload', () => {
    stopAutoRefresh();
});

// Utils
function formatDate(dateString) {
    try {
        const [date, time] = dateString.split(' ');
        const [day, month, year] = date.split('.');
        const dateObj = new Date(year, month - 1, day, ...time.split(':'));
        return dateObj.toLocaleString('fr-FR');
    } catch (e) {
        return dateString;
    }
}

function copyToClipboard(text) {
    if (navigator.clipboard) {
        navigator.clipboard.writeText(text).then(() => {
            showRefreshIndicator('Copié dans le presse-papiers');
        });
    } else {
        const textArea = document.createElement('textarea');
        textArea.value = text;
        document.body.appendChild(textArea);
        textArea.select();
        document.execCommand('copy');
        document.body.removeChild(textArea);
        showRefreshIndicator('Copié dans le presse-papiers');
    }
}

// Export
window.clearSearch = clearSearch;
window.refreshTable = refreshTable;
window.toggleTheme = toggleTheme;
