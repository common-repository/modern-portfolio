filterSelection("all") // Execute the function and show all columns
function filterSelection(c) {
  var x, i;
  x = jQuery("#modern_portfolio .modcolumn");
  if (c == "all") c = "";
  // Add the "show" class (display:block) to the filtered elements, and remove the "show" class from the elements that are not selected
  for (i = 0; i < x.length; i++) {
    modportRemoveClass(x[i], "show");
    if (x[i].className.indexOf(c) > -1)
    modportAddClass(x[i], "show");

  }
}

// Show filtered elements
function modportAddClass(element, name) {
  var i, arr1, arr2;
  arr1 = element.className.split(" ");
  arr2 = name.split(" ");
  for (i = 0; i < arr2.length; i++) {
    if (arr1.indexOf(arr2[i]) == -1) {
      element.className += " " + arr2[i];
    }
  }
}

// Hide elements that are not selected
function modportRemoveClass(element, name) {
  var i, arr1, arr2;
  arr1 = element.className.split(" ");
  arr2 = name.split(" ");
  for (i = 0; i < arr2.length; i++) {
    while (arr1.indexOf(arr2[i]) > -1) {
      arr1.splice(arr1.indexOf(arr2[i]), 1);
    }
  }
  element.className = arr1.join(" ");
}

// Add active class to the current button (highlight it)
var btns = jQuery("#modportBtnContainer .btn");
//var btns = btnContainer.(".btn");
for (var i = 0; i < btns.length; i++) {
  btns[i].addEventListener("click", function(){
    var current = jQuery("#modportBtnContainer .active");
    current[0].className = current[0].className.replace(" active", "");
    this.className += " active";
  });
}