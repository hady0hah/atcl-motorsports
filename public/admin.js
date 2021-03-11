$(document).on('click', '#save_result_button', function() {
    setResultValueToNull();
});

function setResultValueToNull()
{
    var checkbox = $("[id$='dnf']")
    var resultForm = $('.save-result-form');
    if (checkbox.is(':checked')) {
        resultForm.attr('novalidate', 'novalidate');
    }
}