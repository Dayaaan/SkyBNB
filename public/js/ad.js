$('#add-image').click(function(){
    // je récupère le numéro des futurs champs que je vais créer
    const index = +$('#widgets-counter').val(); // le + c'est pour transformer le string en nombre

    // je récupère le prototype des entrées
    const template = $('#annonce_images').data('prototype').replace(/__name__/g,index);

    console.log(template);

    //j'injecte ce code au sein de la div

    $('#annonce_images').append(template);
    $('#widgets-counter').val(index + 1)

    //je gere le boutton supprimer
    handleDeleteButtons();
});
function handleDeleteButtons() {
    $('button[data-action="delete"]').click(function() {
        const target = this.dataset.target;
        console.log(target);
        $(target).remove();
    })
}
function updateCounter() {
    const count= $('#annonce_images div.form-group').length;
    $('#widgets-counter').val(count);
}
updateCounter();
handleDeleteButtons();