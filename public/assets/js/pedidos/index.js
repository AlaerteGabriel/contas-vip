"use strict";

$(document).ready(function () {

    const $table = $('.yajra-datatable'); // referência única da tabela
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
            global:false,
            // Adiciona os dados dos filtros à requisição AJAX
            data: function (d) {
                d.busca = $('#busca').val();
            }
        },
        columns: [
            {data: 'pe_id', name: 'pe_id'},
            {data: 'datavenda', name: 'datavenda', orderable: true},
            {data: 'codigo', name: 'codigo', orderable: true},
            {data: 'detalhe', name: 'detalhe', orderable: false},
            {data: 'servico', name: 'servico', orderable: true},
            {data: 'tipo', name: 'tipo', orderable: false},
            {data: 'inicio', name: 'inicio', orderable: false, searchable: false},
            {data: 'periodo', name: 'periodo', orderable: false, searchable: false},
            {data: 'termino', name: 'termino', orderable: false, searchable: false},
            {data: 'obs', name: 'obs', orderable: false, searchable: false},
        ],
        drawCallback: function() {
        },
        rowCallback: function(row, data) {
        },
        initComplete: function () {
        }

    });

    const buscarTabela = debounce(function() {
        table.draw();
    }, 600); // 300ms (0.3 segundos) é um bom ponto de partida

    // Eventos para recarregar a tabela quando um filtro é alterado
    $('#busca').on('change keyup', buscarTabela);

});
