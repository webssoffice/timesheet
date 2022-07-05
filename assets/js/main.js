let mybutton = document.getElementById("btn-back-to-top");

window.onscroll = function () {
  scrollFunction();
};

function scrollFunction() {
  if (
    document.body.scrollTop > 20 ||
    document.documentElement.scrollTop > 20
  ) {
    mybutton.style.display = "block";
  } else {
    mybutton.style.display = "none";
  }
}

mybutton.addEventListener("click", backToTop);

function backToTop() {
  document.body.scrollTop = 0;
  document.documentElement.scrollTop = 0;
}

document.getElementById('toggle-button').addEventListener('click', function () {
    toggle(document.querySelectorAll('.target'));
});

    function toggle (elements, specifiedDisplay) {
        var element, index;

        elements = elements.length ? elements : [elements];
        
        for (index = 0; index < elements.length; index++) {
            element = elements[index];

            if (isElementHidden(element)) {
                element.style.display = '';

                // If the element is still hidden after removing the inline display
                if (isElementHidden(element)) {
                    element.style.display = specifiedDisplay || 'table-row';
                }
            } else {
                element.style.display = 'none';
            }
        }
    
        function isElementHidden (element) {
        return window.getComputedStyle(element, null).getPropertyValue('display') === 'none';
    }
}