function isValidEmail(email){
    return email.includes("@") && email.includes(".") && email.length > 5;
}