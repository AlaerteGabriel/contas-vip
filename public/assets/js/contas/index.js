"use strict";

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
            global:false,
            // Adiciona os dados dos filtros à requisição AJAX
            data: function (d) {
                d.busca = $('#busca').val();
            }
        },
        columns: [
            {data: 'co_id', name: 'co_id'},
            {data: 'nome', name: 'nome', orderable: true},
            {data: 'codigo', name: 'codigo', orderable: true},
            {data: 'envio_manual', name: 'envio_manual', orderable: false},
            {data: 'acoes', name: 'acoes', orderable: false, searchable: false},
        ],
        drawCallback: function() {

            $('[data-bs-toggle="tooltip"]').tooltip('dispose').tooltip();

            $('.edit').on('click', function(e){
                var id = $(this).attr('data-id');
                // Substitua 'SUA_URL_AQUI' pela URL do seu endpoint JSON
                $.getJSON(ROUTE_EDIT, { idReg:id }, function(dados) {

                    $('#add')[0].reset();

                    $('#id').val(dados.co_id);
                    $('input[name="co_nome"]').val(dados.co_nome);
                    $('input[name="co_codigo"]').val(dados.co_codigo);
                    $('input[name="co_url"]').val(dados.co_url);
                    $('input[name="co_url_anuncio"]').val(dados.co_url_anuncio);
                    $('input[name="co_limite"]').val(dados.co_limite);
                    $('select[name="co_template_email_id"]').val(dados.co_template_email_id).trigger('change');
                    if(dados.co_envio_manual === 1) {
                        $('input[name="co_envio_manual"]').prop('checked', true);
                    }
                });
            });

        },
        rowCallback: function(row, data) {
            $(row).prop('id', data['co_id']);
        },
        initComplete: function () {
        }

    });

    const buscarTabela = debounce(function() {
        table.draw();
    }, 600); // 300ms (0.3 segundos) é um bom ponto de partida

    // Eventos para recarregar a tabela quando um filtro é alterado
    $('#busca').on('change keyup', buscarTabela);

    $(document).on('click', '.btdelete', function(e){

        let idUser = $(this).attr('data-id');

        if(!idUser){
            return false;
        }

        $.ajax({
            url: ROUTE_DELETE,
            type: 'POST',
            dataType: 'json',
            data: {idReg: idUser, _token:TOKEN},
            success: function (data) {
                if(data.ok) {
                    notificar('Registro excluído com sucesso!', 'success');
                    table.ajax.reload();
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.error("Erro ao carregar dados:", textStatus, errorThrown);
            }
        });
    });

});
