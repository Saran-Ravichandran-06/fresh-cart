document.addEventListener("DOMContentLoaded", function () {

  const navbarDiv = document.getElementById("navbar");
  if (navbarDiv) {
    let role = navbarDiv.getAttribute("data-role");
    let navbarFile = "";
    if (role === "buyer") {
      navbarFile = "/fruit_veggie_store/includes/navbar_buyer.html";
    } else if (role === "seller") {
      navbarFile = "/fruit_veggie_store/includes/navbar_seller.html";
    }
    if (navbarFile) {
      fetch(navbarFile)
        .then(res => res.text())
        .then(html => { navbarDiv.innerHTML = html; });
    }
  }


  const footerDiv = document.getElementById("footer");
  if (footerDiv) {
    fetch("/fruit_veggie_store/includes/footer.html")
      .then(res => res.text())
      .then(html => { footerDiv.innerHTML = html; });
  }

  // ===========================
  // SIDEBAR LOADING
  // ===========================

  const sidebarDiv = document.getElementById("sidebar-container");
  if (sidebarDiv) {
    let role = sidebarDiv.getAttribute("data-role");
    let sidebarFile = "";
    if (role === "buyer") {
      sidebarFile = "/fruit_veggie_store/includes/sidebar_buyer.html";
    } else if (role === "seller") {
      sidebarFile = "/fruit_veggie_store/includes/sidebar_seller.html";
    }
    if (sidebarFile) {
      fetch(sidebarFile)
        .then(res => res.text())
        .then(html => {
          sidebarDiv.innerHTML = html;
          document.body.classList.add("has-sidebar");

          // Inject Top Header dynamically
          createTopHeader(role);

          // Add tooltips for collapsed state
          const links = document.querySelectorAll(".sidebar-link");
          links.forEach(link => {
            const textEl = link.querySelector(".sidebar-text");
            if (textEl) {
              link.setAttribute("data-tooltip", textEl.textContent.trim());
            }
          });

          // Highlight active page
          highlightActiveSidebarLink();

          // Restore sidebar state from localStorage
          restoreSidebarState();

          // Create mobile menu button
          createMobileMenuBtn();

          // Create overlay for mobile
          createMobileOverlay();
        });
    }
  }
});

function restoreSidebarState() {
  const sidebar = document.getElementById("sidebar");
  if (!sidebar) return;

  const isCollapsed = localStorage.getItem("sidebarCollapsed") === "true";
  if (isCollapsed && window.innerWidth > 768) {
    sidebar.classList.add("collapsed");
    document.body.classList.add("sidebar-collapsed");
  }
}

// ===========================
// HIGHLIGHT ACTIVE LINK
// ===========================

function highlightActiveSidebarLink() {
  const currentPath = window.location.pathname;
  const links = document.querySelectorAll(".sidebar-link");

  links.forEach(link => {
    const href = link.getAttribute("href");
    if (href && currentPath.includes(href.replace("/fruit_veggie_store", ""))) {
      link.classList.add("active");
    }
  });
}

// ===========================
// MOBILE SUPPORT
// ===========================

function createMobileMenuBtn() {
  if (document.getElementById("mobileMenuBtn")) return;

  const btn = document.createElement("button");
  btn.id = "mobileMenuBtn";
  btn.className = "mobile-menu-btn";
  btn.innerHTML = "☰";
  btn.onclick = function () {
    const sidebar = document.getElementById("sidebar");
    if (sidebar) {
      sidebar.classList.toggle("mobile-open");
      const overlay = document.getElementById("sidebarOverlay");
      if (overlay) {
        overlay.classList.toggle("active", sidebar.classList.contains("mobile-open"));
      }
    }
  };
  document.body.appendChild(btn);
}

function createMobileOverlay() {
  if (document.getElementById("sidebarOverlay")) return;

  const overlay = document.createElement("div");
  overlay.id = "sidebarOverlay";
  overlay.className = "sidebar-overlay";
  overlay.onclick = function () {
    const sidebar = document.getElementById("sidebar");
    if (sidebar) {
      sidebar.classList.remove("mobile-open");
    }
    overlay.classList.remove("active");
  };
  document.body.appendChild(overlay);
}

function createTopHeader(role) {
  if (document.querySelector(".top-header")) return;
  
  const header = document.createElement("header");
  header.className = "top-header";
  
  // Derive page title from the document <title> or <h2>
  const pageTitle = document.title.split(' - ')[0] || (role === 'buyer' ? 'Buyer Portal' : 'Seller Dashboard');
  const roleLabel = role === 'buyer' ? 'Buyer' : 'Seller';
  
  header.innerHTML = `
    <h2>${pageTitle}</h2>
    <div class="top-header-info">
      <span>🌿 ${roleLabel} Account</span>
    </div>
  `;
  
  const sidebarContainer = document.getElementById("sidebar-container");
  if (sidebarContainer && sidebarContainer.nextSibling) {
    sidebarContainer.parentNode.insertBefore(header, sidebarContainer.nextSibling);
  } else {
    document.body.insertBefore(header, document.body.firstChild);
  }
}