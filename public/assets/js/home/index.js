"use strict";
function getDashStats() {
    $.ajax({
        url: BASE_URL_DASHBOARD + '/dash-info',
        type: 'GET',
        dataType: 'json',
        // Essa é a propriedade que impede o bloqueio da página
        global: false,
        success: function (data) {
            if (data) {
                // ... seu código de contagem aqui ...
                const count1 = new countUp.CountUp("total-clientes", data.clientes, {suffix: '+'});
                count1.start();

                const count2 = new countUp.CountUp("total-servicos", data.servicos, {suffix: '+'});
                count2.start();

                const count3 = new countUp.CountUp("total-pedidos", data.pedidos, {suffix: '+'});
                count3.start();

                const count4 = new countUp.CountUp("total-contas", data.contas, {suffix: '+'});
                count4.start();
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.error("Erro ao carregar dados:", textStatus, errorThrown);
        }
    });
}
$(document).ready(function () {

    var table = $('.yajra-datatable').DataTable({
        // lfrtip
        // f -> search box (busca)
        // r -> processing display
        // t -> tabela
        // i -> informações ("Mostrando X de Y")
        // p -> paginação
        // B -> BOTÕES DE AÇÃO, PDF, IMPRIMI etc.
        dom: 'rtip', // garante que o filtro (search) aparece
        buttons: [
            { extend: 'copyHtml5', footer: true },
            { extend: 'csvHtml5', footer: true },
            {
                extend: 'excelHtml5',
                exportOptions: {
                    modifier: {
                        page: 'all',
                        search: 'none'
                    }
                },
                footer: true,
                sheetName: 'Registros'
            },
            { extend: 'pdfHtml5', footer: true },
            { extend: 'print', footer: true }
        ],
        //"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
        pageLength: 20,
        processing: true,
        serverSide: true,
        //'responsive':true,
        "autoWidth": false,
        language: {
            "url": BASE_URL+"/assets/dataTables.pt_BR.json"
        },
        ajax: {
            url: ROUTE_DATATABLES,
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': TOKEN
            },
            global:false,
        },
        columns: [
            {data: 'cl_id', name: 'cl_id'},
            {data: 'nome', name: 'nome', orderable: true},
            {data: 'email', name: 'email', orderable: true},
            {data: 'cel', name: 'cel', orderable: false},
            {data: 'banido', name: 'banido', orderable: false},
        ],
        drawCallback: function() {

            $('[data-bs-toggle="tooltip"]').tooltip('dispose').tooltip();

        },
        rowCallback: function(row, data) {
            $(row).prop('id', data['cl_id']);
        },
        initComplete: function () {
        }

    });

    getDashStats();

});
