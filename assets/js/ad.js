$('#add-image').click(function() {
    // Je récupere le numéro des futurs champs que je vais créer
    const index = +$('#widget-counter').val();
    // Je recupère le prototype des entrées
    const tmpl = $('#ad_images').data('prototype').replace(/__name__/g, index);
    // J'injecte ce code au sein de la div
    $('#ad_images').append(tmpl);
    $('#widget-counter').val(index + 1);
    // Je gère le bouton supprimer
    handleDeleteButtons();
});

function handleDeleteButtons() {
    $('button[data-action="delete"]').click(function() {
        const target = this.dataset.target;
        $(target).remove();
    });
}

function updateCounter() {
    const count = +$('#ad_images div.form-group').length;
    $('#widget-counter').val(count);
}

updateCounter();
handleDeleteButtons();