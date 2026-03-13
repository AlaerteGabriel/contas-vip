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
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': TOKEN
            },
            // Adiciona os dados dos filtros à requisição AJAX
            data: function (d) {
                d.busca = $('#busca').val();
            }
        },
        columns: [
            {data: 'te_id', name: 'te_id'},
            {data: 'codigo', name: 'codigo', orderable: false},
            {data: 'assunto', name: 'assunto', orderable: true},
            {data: 'acoes', name: 'acoes', orderable: false, searchable: false},
        ],
        drawCallback: function() {

            $('[data-bs-toggle="tooltip"]').tooltip('dispose').tooltip();

            $('.edit').on('click', function(e){
                var id = $(this).attr('data-id');
                // Substitua 'SUA_URL_AQUI' pela URL do seu endpoint JSON
                $.getJSON(ROUTE_EDIT, { idReg:id }, function(dados) {
                    $('#add')[0].reset();

                    $('#id').val(dados.te_id);
                    $('input[name="te_assunto"]').val(dados.te_assunto);
                    $('textarea[name="te_modelo"]').text(dados.te_modelo);
                    $('input[name="te_codigo"]').val(dados.te_codigo);
                });
            });

        },
        rowCallback: function(row, data) {
            $(row).prop('id', data['sm_id']);
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

document.addEventListener('DOMContentLoaded', function() {
    const tags = document.querySelectorAll('.table code');
    const editor = document.getElementById('editor');
    const previewModal = document.getElementById('previewModal');
    const previewIframe = document.getElementById('previewIframe');

    tags.forEach(tag => {
        tag.style.cursor = 'pointer';
        tag.title = 'Clique para copiar';

        tag.addEventListener('click', function() {
            const text = this.innerText;

            // Fallback for document.execCommand('copy') which doesn't require HTTPS/localhost
            const textArea = document.createElement("textarea");
            textArea.value = text;

            // Move textarea out of viewport
            textArea.style.position = "absolute";
            textArea.style.left = "-999999px";

            document.body.appendChild(textArea);
            textArea.select();

            try {
                document.execCommand('copy');

                const originalHtml = this.innerHTML;

                // Feedback UI
                this.innerHTML = '<i class="fa-solid fa-check text-success"></i> <span class="text-success fw-bold">Copiado!</span>';
                this.classList.add('bg-success', 'bg-opacity-10', 'border-success');

                setTimeout(() => {
                    this.innerHTML = originalHtml;
                    this.classList.remove('bg-success', 'bg-opacity-10', 'border-success');
                }, 1500);
            } catch (err) {
                console.error('Falha ao copiar:', err);
            } finally {
                // Cleanup
                textArea.remove();
            }
        });
    });

    // 2. Preview Modal logic
    if (previewModal) {
        previewModal.addEventListener('show.bs.modal', function () {
            const htmlContent = editor.value;
            const iframeDoc = previewIframe.contentDocument || previewIframe.contentWindow.document;

            iframeDoc.open();

            if (htmlContent.trim() === '') {
                iframeDoc.write('<div style="font-family: sans-serif; text-align: center; color: #6c757d; margin-top: 50px;">Nenhum conteúdo HTML foi digitado no editor.</div>');
            } else {
                // Caso o usuário não tenha digitado a estrutura HTML básica e só um texto com h1/p,
                // envolvemos em um body com fonte bonita para a visualização não ficar rústica.
                if(!htmlContent.toLowerCase().includes('<html')) {
                    iframeDoc.write(`
                            <!DOCTYPE html>
                            <html>
                            <head>
                                <meta charset="utf-8">
                                <style>body { font-family: 'Inter', 'Segoe UI', sans-serif; color: #333; line-height: 1.6; padding: 20px; }</style>
                            </head>
                            <body>${htmlContent}</body>
                            </html>
                        `);
                } else {
                    // Se for um HTML formado
                    iframeDoc.write(htmlContent);
                }
            }

            iframeDoc.close();
        });
    }
});
