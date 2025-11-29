function isValidEmail(email){
    return email.includes("@") && email.includes(".") && email.length > 5;
}

const contactForm = document.getElementById("contactForm");

if(contactForm){
    contactForm.addEventListener("submit", function(e){
        
        const name = document.getElementById("contactName").value.trim();
        const email = document.getElementById("contactEmail").value.trim();
        const msg = document.getElementById("contactMessage").value.trim();

        if(name.length < 3){
            alert("Emri duhet të ketë të paktën 3 karaktere.");
            e.preventDefault();
        }
        else if(!isValidEmail(email)){
            alert("Ju lutem vendosni një email valid.");
            e.preventDefault();
        }
        else if(msg.length < 10){
            alert("Mesazhi duhet të ketë të paktën 10 karaktere.");
            e.preventDefault();
        }
    });
}