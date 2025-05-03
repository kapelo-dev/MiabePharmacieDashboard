document.addEventListener('DOMContentLoaded', function() {
    const menuItems = document.querySelectorAll('.nav-link');
    const currentPath = window.location.pathname;
  
    // Fonction pour désactiver tous les éléments de menu
    function deactivateAllMenuItems() {
      menuItems.forEach(item => {
        item.classList.remove('active', 'bg-gradient-dark', 'text-white');
        item.classList.add('text-dark');
      });
    }
  
    // Fonction pour activer l'élément de menu correspondant à l'URL actuelle
    function activateMenuItemBasedOnURL() {
      deactivateAllMenuItems();
      menuItems.forEach(item => {
        if (item.getAttribute('href') === currentPath || (item.getAttribute('href') === '/' && currentPath === '/')) {
          item.classList.add('active', 'bg-success', 'text-white');
          item.classList.remove('text-dark');
        }
      });
    }
  
    // Activer l'élément de menu correspondant à l'URL actuelle au chargement de la page
    activateMenuItemBasedOnURL();
  
    // Ajoute un gestionnaire d'événements pour les clics sur les liens
    menuItems.forEach(item => {
      item.addEventListener('click', function(event) {
        // Désactive tous les éléments de menu
        deactivateAllMenuItems();
  
        // Ajoute la classe active à l'élément cliqué
        this.classList.add('active', 'bg-success', 'text-white');
        this.classList.remove('text-dark');
      });
    });
  });
  