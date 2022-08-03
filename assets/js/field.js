function clearText(theField) {
    if (theField.defaultValue == theField.value)
        theField.value = '';
}

function addText(theField) {
    if (theField.value == '')
        theField.value = theField.defaultValue;
}

function confirmm(redirect) {
    if (confirm("你確定要執行此操作嗎?")) {
        window.location.href = redirect;
    }
}
