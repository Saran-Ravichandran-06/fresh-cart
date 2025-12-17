document.addEventListener("DOMContentLoaded", function() {
  
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
});