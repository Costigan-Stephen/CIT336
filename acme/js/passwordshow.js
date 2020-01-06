function passwordShow($field="clientPassword", $e="showhide") {
    var x = document.getElementById($field);
    if (x.type === "password") {
      x.type = "text";
      document.getElementById($e).innerHTML = "Hide Password";
    } else {
      x.type = "password";
      document.getElementById($e).innerHTML = "Show Password";
    }
  }