function passwordShow() {
    var x = document.getElementById("clientPassword");
    if (x.type === "password") {
      x.type = "text";
      document.getElementById("showhide").innerHTML = "Hide Password";
    } else {
      x.type = "password";
      document.getElementById("showhide").innerHTML = "Show Password";
    }
  }