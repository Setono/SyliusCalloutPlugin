$(document).ready(() => {
    $('#rules a[data-form-collection="add"]').on('click', () => {
        setTimeout(() => {
            $('select[name^="callout[rules]"][name$="[type]"]').last().change();
        }, 50);
    });
});
