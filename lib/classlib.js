function SwalOnlyText(icon, title, text){
    Swal.fire({
        icon: icon,
        title: title,
        text: text,
    })
}

function SwalOnlyLoad(text){
    Swal.fire({
        text: text,
        imageUrl: 'lib.albatrosslogistic.com/assets/gif/ajax-loader.gif',
        showConfirmButton: false,
        showCancelButton: false,
        timer: 1500,
    })
}

function SwalReload(icon, title, text, route, target = '_blank'){
    Swal.fire({
        icon: icon,
        title: title,
        text: text,
    }).then(() => {
        if(route != null){
            window.open(route, target)
        }
        location.reload()
    })
}

function GritterRegular(title, text){
    $.gritter.add({
        title: title,
        text: text,
        sticky: false,
        // image: '../assets/img/user/user-3.jpg',
        // time: ''
    });
    return false;
}

function OpenViewDetail(id, route, parameter){
    $(id).load(route, { sendingTask: parameter }, function(){
        $(this).modal('show');
    })
}

function MakeID(length) {
    let result = '';
    const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    const charactersLength = characters.length;
    let counter = 0;
    while (counter < length) {
      result += characters.charAt(Math.floor(Math.random() * charactersLength));
      counter += 1;
    }
    return result;
}


var imgLoader = 'https://lib.albatrosslogistic.com/assets/gif/ajax-loader.gif'