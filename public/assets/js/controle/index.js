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
            {data: 'cs_id', name: 'cs_id'},
            {data: 'servico', name: 'servico', orderable: true},
            {data: 'codigo', name: 'codigo', orderable: true},
            {data: 'email_envio', name: 'email', orderable: false},
            {data: 'username', name: 'username', orderable: true},
            {data: 'senha', name: 'senha', orderable: false, searchable: false},
            {data: 'dt_renovacao', name: 'dt_renovacao', orderable: false, searchable: false},
            {data: 'qtd_update', name: 'qtd_update', orderable: false, searchable: false},
            {data: 'status', name: 'status', orderable: false, searchable: false},
            {data: 'emailad', name: 'emailad', orderable: false, searchable: false},
            {data: 'obs', name: 'obs', orderable: false, searchable: false},
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
            console.log(data);
            $(row).prop('id', data['cs_id']);
            if(data['cs_status'] == 'suspenso' || data.cliente['cl_banido']==1){
                $(row).addClass('opacity-25');
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

    $(document).on('click', '.btnTrocarConta', function(e){

        let id = $(this).attr('data-id');
        let idCliente = $(this).attr('data-id-cliente');
        let nome = $(this).attr('data-cliente');
        // (Opcional) Passa o ID capturado para algum input escondido dentro da modal
        $('#idCliente').val(idCliente);
        $('#idCs').val(id);
        $('.nomeCliente').empty().text('Cliente: '+nome);

        // Instancia e abre a modal usando a API do BS5
        const minhaModal = new bootstrap.Modal(document.getElementById('trocarClienteModal'));
        minhaModal.show();

    });

    $(document).on('click', '.btnBanirCliente', function(e){

        let id = $(this).attr('data-id');

        if(!id){
            return false;
        }

        Swal.fire({
            title: 'Deseja realmente banir este cliente?',
            text: 'Caso o cliente seja banido, ele deixará de receber toda e qualquer atualização do sistema.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Sim, continuar',
        }).then((result) => {

            if (result.value) {
                $.ajax({
                    url: ROUTE_BAN,
                    type: 'POST',
                    dataType: 'json',
                    data: {idReg: id, _token:TOKEN},
                    success: function (data) {
                        if(data.ok) {
                            notificar('Cliente banido sucesso!', 'success');
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

    $(document).on('click', '.btnSuspender', function(e){
        let id = $(this).attr('data-id');

        if(!id){
            return false;
        }

        Swal.fire({
            title: 'Deseja realmente banir o cliente deste serviço?',
            text: 'Caso o cliente seja banido do serviço, ele deixará de receber toda e qualquer atualização do serviço.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Sim, continuar',
        }).then((result) => {

            if (result.value) {
                $.ajax({
                    url: ROUTE_BAN_SERV,
                    type: 'POST',
                    dataType: 'json',
                    data: {idReg: id, _token:TOKEN},
                    success: function (data) {
                        if(data.ok) {
                            notificar('Cliente banido do serviço sucesso!', 'success');
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

    $(document).on('click', '.btnLiberar', function(e){
        let id = $(this).attr('data-id');

        if(!id){
            return false;
        }

        Swal.fire({
            title: 'Deseja reativar o cliente neste serviço?',
            text: 'Caso o cliente seja reativado no serviço, ele passará a receber toda e qualquer atualização do serviço.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Sim, continuar',
        }).then((result) => {

            if (result.value) {
                $.ajax({
                    url: ROUTE_BAN_SERV,
                    type: 'POST',
                    dataType: 'json',
                    data: {idReg: id, _token:TOKEN},
                    success: function (data) {
                        if(data.ok) {
                            notificar('Cliente reativado ao serviço com sucesso!', 'success');
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

    $(document).on('click', '.btnEmailAdicional', function(e){

        let id = $(this).attr('data-id');
        $('#idCsMail').val(id);

        // Instancia e abre a modal usando a API do BS5
        const minhaModal = new bootstrap.Modal(document.getElementById('addEmailAdicionalModal'));
        minhaModal.show();

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

    $('#addEmailAdicionalModal').on('shown.bs.modal', function () {
        $('.select2').val(null).trigger('change');
        $('.select2').select2({
            theme: 'bootstrap-5',
            width: '100%',
            dropdownParent: $('#addEmailAdicionalModal')
        });
    });

    $('#trocarClienteModal').on('shown.bs.modal', function () {
        // limpa o select
        $('.select2').val(null).trigger('change');
        $('.select2').select2({
            theme: 'bootstrap-5',
            width: '100%',
            dropdownParent: $('#trocarClienteModal')
        });
    });

    // $(document).on('shown.bs.modal', '.modal', function () {
    //
    //     $(this).find('.select2').each(function () {
    //
    //         if (!$(this).hasClass("select2-hidden-accessible")) {
    //
    //             $(this).select2({
    //                 theme: 'bootstrap-5',
    //                 width: '100%',
    //                 language: 'pt',
    //                 dropdownParent: $(this).closest('.modal')
    //             });
    //
    //         }
    //
    //     });
    //
    // });

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

});
