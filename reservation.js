  // Cette fonction sera appelée après la soumission du formulaire
  function afficherConfirmation() {
    var confirmationDiv = document.getElementById("confirmation");
    confirmationDiv.style.display = "block";  // Afficher la confirmation
    
    // Cacher la confirmation après 5 secondes (5000 millisecondes)
    setTimeout(function() {
        confirmationDiv.style.display = "none";
    }, 5000);
}


document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("reservationForm");
    const reserveButton = document.getElementById("reserveButton");
    const resetButton = document.getElementById("resetButton");

    // Animation du bouton "Réserver" avec un message visuel
    reserveButton.addEventListener("click", (event) => {
        event.preventDefault();
        reserveButton.innerHTML = "Réservation envoyée 🎉";
        reserveButton.style.backgroundColor = "#FFD700";
        reserveButton.disabled = true;

        const successMessage = document.createElement("p");
        successMessage.innerText = "Merci pour votre réservation .";
        successMessage.style.color = "green";
        successMessage.style.fontWeight = "bold";
        successMessage.style.marginTop = "20px";

        form.appendChild(successMessage);

        setTimeout(() => {
            form.submit();
        }, 2000);
    });

    // Réinitialisation avec un message doux
    resetButton.addEventListener("click", () => {
        reserveButton.innerHTML = "Réserver";
        reserveButton.style.backgroundColor = "#FF5733";
        reserveButton.disabled = false;

        // Supprime les messages ajoutés dynamiquement
        const successMessage = form.querySelector("p");
        if (successMessage) successMessage.remove();
    });
});




