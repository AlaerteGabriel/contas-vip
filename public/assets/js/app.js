"use strict";

// const Toast = Swal.mixin({
//     toast: true,
//     position: "top-end",
//     showConfirmButton: false,
//     timer: 3000,
//     timerProgressBar: true,
//     didOpen: (toast) => {
//         toast.onmouseenter = Swal.stopTimer;
//         toast.onmouseleave = Swal.resumeTimer;
//     }
// });
//
// Toast.fire({
//     icon: "success",
//     title: "Signed in successfully"
// });

iziToast.settings({
    timeout: 10000,
    resetOnHover: true,
    transitionIn: 'flipInX',
    transitionOut: 'flipOutX',
    onOpening: function(){

    },
    onClosing: function(){

    }
});

/**
 * Função utilitária de Debounce.
 * @param {function} func - A função a ser executada.
 * @param {number} delay - O atraso em milissegundos.
 */
function debounce(func, delay) {
    let timeout;
    return function() {
        const context = this;
        const args = arguments;
        clearTimeout(timeout);
        timeout = setTimeout(() => func.apply(context, args), delay);
    };
}

function notificar(msg, tipo = 'success', time = 3000)
{

    switch(tipo){

        case 'error':
            iziToast.error({
                title: 'Ops!!',
                message: msg,
                timeout: time,
                position: 'bottomRight',
                onClosed: function () {}
            });
            break;

        case 'success':
            iziToast.success({
                title: 'Sucesso!',
                message: msg,
                position: 'bottomRight',
                timeout: time,
                onClosed: function () {}
            });
            break;

        case 'warning':
            iziToast.warning({
                title: 'Atenção:',
                message: msg,
                position: 'bottomRight',
                timeout: time,
                onClosed: function () {}
            });
            break;

        case 'info':
            iziToast.info({
                title: 'INFO:',
                message: msg,
                position: 'bottomRight',
                timeout: time,
                onClosed: function () {}
            });
            break;
    }

}
