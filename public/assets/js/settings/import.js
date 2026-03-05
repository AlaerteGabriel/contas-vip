var uppy = Uppy.Core({

    debug: true,
    autoProceed: false,
    allowMultipleUploads: false,
    restrictions: {
        minNumberOfFiles: 1,
        allowedFileTypes: ['application/excel', 'application/csv', 'text/csv', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.ms-excel', 'application/msexcel']
    }

}).use(Uppy.Dashboard, {

    inline: true,
    target: '#uppy',
    height: 240,
    width: '100%',
    metaFields: [{
        id: 'nfe',
        name: 'file',
        placeholder: 'file name'
    }],
    //locale: Uppy.locales.pt_BR,
    locale: {
        strings:{
            addMore:"Adicione mais",
            addMoreFiles:"Adicionar mais arquivos",
            addingMoreFiles:"Adicionando mais arquivos",
            allowAccessDescription:"Para poder tirar fotos e gravar v\xeddeos com sua c\xe2mera, por favor permita o acesso a c\xe2mera para esse site.",
            allowAccessTitle:"Por favor permita o acesso a sua c\xe2mera",
            authenticateWith:"Conectar com %{pluginName}",
            authenticateWithTitle:"Por favor conecte com %{pluginName} para selecionar arquivos",
            back:"Voltar",
            browse:"Procurar suas listas Excel (xlsx).",
            browseFiles:"Procurar suas listas Excel (xlsx).",
            cancel:"Cancelar",
            cancelUpload:"Cancelar envio de arquivos",
            chooseFiles:"Selecionar arquivos",
            closeModal:"Fechar Modal",
            companionError:"Conex\xe3o com servi\xe7o falhou",
            complete:"Conclu\xeddo",
            connectedToInternet:"Conectado \xe1 internet",
            copyLink:"Copiar link",
            copyLinkToClipboardFallback:"Copiar URL abaixo",
            copyLinkToClipboardSuccess:"Link copiado para a \xe1rea de transfer\xeancia",
            creatingAssembly:"Preparando envio de arquivos...",
            creatingAssemblyFailed:"Transloadit: N\xe3o foi poss\xedvel criar o Assembly",
            dashboardTitle:"Envio de arquivos",
            dashboardWindowTitle:"Janela para envio de arquivos (Pressione esc para fechar)",
            dataUploadedOfTotal:"%{complete} de %{total}",
            done:"Concluir",
            dropHereOr:"Arraste arquivos aqui ou %{browse}",
            dropHint:"Solte seus arquivos aqui",
            dropPasteBoth:"Arraste aqui sua lista de preços ou clique aqui para %{browse}",
            dropPasteFiles:"Arraste aqui sua lista de preços ou clique aqui para %{browse}",
            dropPasteFolders:"Arraste aqui sua lista de preços ou clique aqui para %{browse}",
            dropPasteImportBoth:"Solte arquivos aqui, cole, %{browse} ou importe de",
            dropPasteImportFiles:"Solte arquivos aqui, cole, %{browse} ou importe de",
            dropPasteImportFolders:"Solte arquivos aqui, cole, %{browse} ou importe de",
            editFile:"Editar arquivo",
            editing:"Editando %{file}",
            emptyFolderAdded:"Nenhum arquivo foi adicionado da pasta vazia",
            encoding:"Codificando...",
            enterCorrectUrl:"URL incorreta: Por favor tenha certeza que inseriu um link direto para um arquivo",
            enterUrlToImport:"Coloque a URL para importar um arquivo",
            exceedsSize:"Esse arquivo excedeu o tamanho m\xe1ximo permitido %{size}",
            failedToFetch:"Servi\xe7o falhou para buscar essa URL, por favor tenha certeza que a URL est\xe1 correta",
            failedToUpload:"Falha para enviar %{file}",
            fileSource:"Origem do arquivo: %{name}",
            filesUploadedOfTotal:{
                0:"%{complete} de %{smart_count} arquivo enviado",
                1:"%{complete} de %{smart_count} arquivos enviados"
            },
            filter:"Filtrar",
            finishEditingFile:"Finalizar edi\xe7\xe3o de arquivo",
            folderAdded:{
                0:"Adicionado %{smart_count} arquivo de %{folder}",
                1:"Adicionado %{smart_count} arquivos de %{folder}"
            },
            import:"Importar",
            importFrom:"Importar de %{name}",
            loading:"Carregando...",
            logOut:"Deslogar",
            myDevice:"Meu dispositivo",
            noFilesFound:"Voc\xea n\xe3o possui arquivos ou pastas aqui",
            noInternetConnection:"Sem conex\xe3o com a internet",
            pause:"Pausar",
            pauseUpload:"Pausar envio de arquivos",
            paused:"Pausado",
            poweredBy:"Desenvolvido por %{uppy}",
            processingXFiles:{
                0:"Processando %{smart_count} arquivo",
                1:"Processando %{smart_count} arquivos"
            },
            removeFile:"Remover arquivo",
            resetFilter:"Resetar filtro",
            resume:"Retomar",
            resumeUpload:"Retomar envio de arquivos",
            retry:"Tentar novamente",
            retryUpload:"Tentar enviar novamente",
            saveChanges:"Salvar altera\xe7\xf5es",
            selectX:{
                0:"Selecionar %{smart_count}",
                1:"Selecionar %{smart_count}"
            },
            smile:"Sorria!",
            startRecording:"Come\xe7ar grava\xe7\xe3o de v\xeddeo",
            stopRecording:"Parar grava\xe7\xe3o de v\xeddeo",
            takePicture:"Tirar uma foto",
            timedOut:"Envio de arquivos parado por %{seconds} segundos, abortando.",
            upload:"Enviar arquivos",
            uploadComplete:"Envio de arquivos finalizado",
            uploadFailed:"Envio de arquivos falhou",
            uploadPaused:"Envio de arquivos pausado",
            uploadXFiles:{
                0:"Enviar %{smart_count} arquivo",
                1:"Enviar %{smart_count} arquivos"
            },
            uploadXNewFiles:{
                0:"Enviar +%{smart_count} arquivo",
                1:"Enviar +%{smart_count} arquivos"
            },
            uploading:"Enviando",
            uploadingXFiles:{
                0:"Enviando %{smart_count} arquivo",
                1:"Enviando %{smart_count} arquivos"
            },
            xFilesSelected:{
                0:"%{smart_count} arquivo selecionado",
                1:"%{smart_count} arquivos selecionados"
            },
            xMoreFilesAdded:{
                0:"%{smart_count} arquivo adicionados",
                1:"%{smart_count} arquivos adicionados"
            },
            xTimeLeft:"%{time} restantes",
            youCanOnlyUploadFileTypes:"Voc\xea pode enviar apenas arquivos: %{types}",
            youCanOnlyUploadX:{
                0:"Voc\xea pode enviar apenas %{smart_count} arquivo",
                1:"Voc\xea pode enviar apenas %{smart_count} arquivos"
            },
            youHaveToAtLeastSelectX:{
                0:"Voc\xea precisa selecionar pelo menos %{smart_count} arquivo",
                1:"Voc\xea precisa selecionar pelo menos %{smart_count} arquivos"
            },
            selectFileNamed:"Selecione o arquivo %{name}",
            unselectFileNamed:"Deselecionar arquivo %{name}",
            openFolderNamed:"Pasta aberta %{name}"
        }
    }

}).use(Uppy.XHRUpload, {

    timeout: 0,
    limit: 2,
    endpoint: BASE_URL_DASHBOARD+'/config/produtosstorecsv?_token='+token,
    method: 'POST',
    formData: true,
    fieldName: 'file',
    headers : {
        'X-Requested-With': 'XMLHttpRequest'
    },
    getResponseError(content, xhr) {
        //console.log(content);
    }

});

// Adicionar um novo parâmetro (metadado global)
//uppy.setMeta({ userId: '12345', token: 'abc123' });

var tentativa = 0;

toastr.options.onHidden = function() {
    //redir('');
}

uppy.on('upload-error', (file, error, result) => {

    toastr.warning('Falha no upload, não se preocupe, estamos tentando novamente.', 'Oops!');
    //console.log('error with file:', file.id);
    //console.log('error message:', error);

    if(tentativa === 0) {
        uppy.retryUpload(file.id);
    }else if(tentativa > 0){
        uppy.removeFile(file.id);
        uppy.retryAll();
    }
});

uppy.on('upload-retry', (fileID) => {
    tentativa+=1;
    //console.log('upload retried:', fileID);
});

uppy.on('upload-success', (file, result) => {

    console.log(result);

    if(result.body.ok == 3){

        Swal.fire('Oops!', result.body.erro, "error").then(function() {
            uppy.removeFile(file.id);
            //redir('');
        });

    }

});

uppy.on('complete', (result) => {
    //console.log('successful files:', result.successful);
    // console.log('failed files:', result.failed);

    if (result.successful.length > 0) {

        if(result.successful[0].response.body.ok == 1){
            toastr.success('Dados importados com sucesso!', 'Tudo certo!');
        }else if(result.successful[0].response.body.ok != 1){
            //toastr.error('Houve uma instabilidade no sistema, tente novamente.', 'Oops!');
            Swal.fire('Oops!', 'Houve uma instabilidade no sistema, tente novamente.', "error").then(function() {
                redir('');
            });
        }

    }
});
