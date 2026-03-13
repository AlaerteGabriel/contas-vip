"use strict";

function getDashStats() {
    $.ajax({
        url: BASE_URL_DASHBOARD + '/servicos/serv-info',
        type: 'GET',
        dataType: 'json',
        // Essa é a propriedade que impede o bloqueio da página
        global: false,
        success: function (data) {
            if (data) {
                // ... seu código de contagem aqui ...
                const count1 = new countUp.CountUp("total-servicos", data.servicos, {suffix: '+'});
                count1.start();

                const count2 = new countUp.CountUp("total-servicos-ativos", data.servicos_ativos, {suffix: '+'});
                count2.start();

                const count3 = new countUp.CountUp("total-renovar", data.renovacoes, {suffix: '+'});
                count3.start();

            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.error("Erro ao carregar dados:", textStatus, errorThrown);
        }
    });
}

$(document).ready(function () {

    getDashStats();

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
            {data: 'se_id', name: 'se_id'},
            {data: 'codigo', name: 'codigo', orderable: true},
            {data: 'nome', name: 'nome', orderable: true},
            {data: 'email', name: 'email', orderable: false},
            {data: 'username', name: 'username', orderable: true},
            {data: 'senha', name: 'senha', orderable: false},
            {data: 'dt_renovacao', name: 'dt_renovacao', orderable: false},
            {data: 'tipo', name: 'tipo', orderable: false},
            {data: 'dt_update', name: 'dt_update', orderable: false},
            {data: 'qtdAssinantes', name: 'qtdAssinantes', orderable: false},
            {data: 'limite', name: 'limite', orderable: false},
            {data: 'status', name: 'status', orderable: false},
            {data: 'acoes', name: 'acoes', orderable: false, searchable: false},
        ],
        drawCallback: function() {

            $('[data-bs-toggle="tooltip"]').tooltip('dispose').tooltip();
            // Custom JS Just for copy passwords inside inputs
            document.querySelectorAll('button[title="Copiar"]').forEach(btn => {
                btn.addEventListener('click', function () {
                    const input = this.previousElementSibling;
                    // temporarely change type to copy if needed, but select works on text/password
                    if (input) {
                        const type = input.type;
                        input.type = "text";
                        input.select();
                        document.execCommand("copy");
                        input.type = type;

                        // visual feedback
                        const originalIcon = this.innerHTML;
                        this.innerHTML = '<i class="fa-solid fa-check text-success"></i>';
                        setTimeout(() => { this.innerHTML = originalIcon; }, 1500);
                    }
                });
            });

        },
        rowCallback: function(row, data) {

            $(row).prop('id', data['se_id']);

            if(data['se_status'] == 'desligada'){
                $(row).addClass('opacity-75 bg-light');
            }
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

        Swal.fire({
            title: 'Cuidado! Deseja realmente apagar?',
            text: 'Essa operação não poderá ser desfeita.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Sim, apague',
        }).then((result) => {

            if (result.value) {
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
            }

        });

    });

    $('.select2').select2({
        width: '100%',
        language: "pt",
        theme: 'bootstrap-5',
        dropdownParent: $('.modal-body'),
    });

    $(document).on('click', '.btnaltSenha', function(e){

        let id = $(this).attr('data-id');
        let senha = $(this).attr('data-senha');
        let status = $(this).attr('data-status');

        $('#idSe').val(id);
        $('#status').val(status).trigger('change');
        $('#senha').val(senha);

        // Instancia e abre a modal usando a API do BS5
        const minhaModal = new bootstrap.Modal(document.getElementById('altsenhaModal'));
        minhaModal.show();

    });

});
