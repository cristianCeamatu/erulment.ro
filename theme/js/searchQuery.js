// IN URLul PENTRU CAUTARE VOR APAREA DOAR SELECTIILE COMPLETATE
function cautareInUrl()
{
    var myForm = document.getElementById('formCautare');
    var allInputs = myForm.getElementsByTagName('input');
    var input, i;

    for(i = 0; input = allInputs[i]; i++) {
        if(input.getAttribute('name') && !(/\S/.test(input.value)) ) {
            input.setAttribute('name', '');
        }
    }
}
