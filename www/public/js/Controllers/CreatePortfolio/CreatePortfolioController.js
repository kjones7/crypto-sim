// import $ from 'jquery';
// import 'bootstrap/dist/css/bootstrap.min.css';
// import 'bootstrap/dist/js/bootstrap.bundle';

$(document).ready(function() {
    var addUserButton = $('#add-invite');
    var numInvites = parseInt($('[name=num-invites]')[0].value);

    addUserButton.on('click', function(e) {
        var selectHTML = addUserButton.parent().find('[name=invite1]')[0].outerHTML;
        addUserButton.parent().append(selectHTML);
        numInvites += 1;
        $('[name=num-invites]')[0].value = (parseInt($('[name=num-invites]')[0].value) + 1).toString();
        $('#invite-wrapper').children().last()[0].name = 'invite' + numInvites;
    });

    $('#portfolio-types').on('click', function(e){
        if(e.target.id === 'group-radio') {
            $('#invite-wrapper').removeClass('d-none');
        } else {
            $('#invite-wrapper').addClass('d-none');
        }
    })
});