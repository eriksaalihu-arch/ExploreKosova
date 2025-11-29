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

const loginForm = document.getElementById("loginForm");

if(loginForm){
    loginForm.addEventListener("submit", function(e){
        
        const email = document.getElementById("loginEmail").value.trim();
        const pass = document.getElementById("loginPassword").value.trim();

        if(!isValidEmail(email)){
            alert("Email nuk është valid.");
            e.preventDefault();
        }
        else if(pass.length < 6){
            alert("Fjalëkalimi duhet të ketë të paktën 6 karaktere.");
            e.preventDefault();
        }
    });
}