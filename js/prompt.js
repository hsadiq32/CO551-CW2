function promptToggler() {
  // counts total checked checkboxes
  var records = document.querySelectorAll('input[type="checkbox"]:checked').length;
  if (records > 0) {
    if (confirm(syntaxFormer(records)) == true) { // custom message
      document.getElementById("studentForm").submit();
    }
  }
  else {
    alert("No rows selected") // does not initiate prompt with no selection
  }
}
// create syntax for message
function syntaxFormer(records) {
  var string = "record";
  if (records > 1) {
    string += "s"; // adds plural clause on multiple inputs
  }
  return "Are you sure you want to delete " + records + " " + string + "?";
}