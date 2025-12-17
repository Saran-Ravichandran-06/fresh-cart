
function confirmDelete() {
    return confirm("Are you sure you want to delete this item?");
}


function togglePassword(id) {
    const input = document.getElementById(id);
    if (input.type === "password") {
        input.type = "text";
    } else {
        input.type = "password";
    }
}


function filterProducts() {
    let input = document.getElementById("searchBox").value.toLowerCase();
    let cards = document.getElementsByClassName("card");
    
    for (let i = 0; i < cards.length; i++) {
        let title = cards[i].getElementsByTagName("h4")[0];
        if (title) {
            let textValue = title.textContent || title.innerText;
            cards[i].style.display = textValue.toLowerCase().indexOf(input) > -1 ? "" : "none";
        }
    }
}

