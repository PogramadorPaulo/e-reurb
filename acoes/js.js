
/* Tabs */
$(document).ready(function () {
    const params = new URLSearchParams(window.location.search);
    const mainTab = params.get('tab'); // Obtém o parâmetro da URL
    // Mapeamento das etapas para as suas abas
    const tabMapping = {
        '1': 'etapa_1',
        '2': 'etapa_2',
        '3': 'etapa_3',
        '4': 'etapa_4',
        '5': 'etapa_5',
        '6': 'etapa_6',
        '7': 'etapa_7'
    };

    // Exibir a aba correta ao carregar a página
    if (mainTab && tabMapping[mainTab]) {
        $(`#etapasTabs a[href="#${tabMapping[mainTab]}"]`).tab('show');
    } else {
        // Se nenhum parâmetro ou inválido, mostra a primeira aba
        $('#etapasTabs a[href="#etapa_1"]').tab('show');
    }

    // Atualizar a URL ao clicar nas abas principais
    $('#etapasTabs a').on('click', function (e) {
        const tabId = $(this).attr('href').replace('#', '');

        if ($(this).hasClass('disabled')) {
            e.preventDefault();
            Swal.fire('Etapa Bloqueada', 'Aguardando concluir etapa anterior', 'warning');
            return;
        }

        const tabNumber = Object.keys(tabMapping).find(key => tabMapping[key] === tabId);
        const newUrl = `${window.location.pathname}?tab=${tabNumber}`;

        $('.tab-pane').removeClass('show active');
        $(`#${tabId}`).addClass('show active');

        // ✅ Carregar modelos apenas se for a aba 5
        if (tabNumber === '5') {
            carregarModelosEtapa5();
        }

        window.history.pushState({ tab: tabId }, '', newUrl);
    });


    // Lidar com o estado do botão "voltar" do navegador
    window.addEventListener('popstate', function (event) {
        const tab = event.state ? event.state.tab : null;
        if (tab && $(`#etapasTabs a[href="#${tab}"]`).length) {
            $(`#etapasTabs a[href="#${tab}"]`).tab('show');
        }
    });

});

/*  status do processo */
$(document).ready(function () {
    const procedimentoId = $('#id').val(); // Captura o ID do procedimento

    function showLoader() {
        Swal.fire({
            title: 'Aguarde...',
            text: 'Processando a solicitação...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
    }

    function closeLoader() {
        Swal.close();
    }

    function handleResponse(response) {
        closeLoader();
        Swal.fire({
            title: response.tittle,
            html: response.message,
            icon: response.icon
        }).then(() => {
            if (response.status === 'success') {
                location.reload();
            }
        });
    }

    function handleError(message) {
        closeLoader();
        Swal.fire('Erro!', message, 'error');
    }

    $('.btn-concluir-etapa').click(function () {
        const etapaId = $(this).data('etapa');
        Swal.fire({
            title: 'Confirmação',
            text: 'Você deseja concluir esta etapa?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sim, concluir',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                showLoader();
                $.ajax({
                    url: '../../acoes/concluir_etapa.php',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        id_procedimento: procedimentoId,
                        id_user: $('#idUser').val(),
                        id_municipio: $('#idMunicipio').val(),
                        etapa: etapaId
                    },
                    success: handleResponse,
                    error: () => handleError('Erro ao concluir a etapa. Tente novamente.')
                });
            }
        });
    });

    $('.marcarPendente').click(function () {
        const etapaId = $(this).data('etapa');
        Swal.fire({
            title: 'Marcar como Pendente',
            input: 'textarea',
            inputPlaceholder: 'Descreva o motivo da pendência...',
            showCancelButton: true,
            confirmButtonText: 'Marcar',
            cancelButtonText: 'Cancelar',
            preConfirm: (descricao) => {
                if (!descricao) {
                    Swal.showValidationMessage('A descrição é obrigatória!');
                }
                return descricao;
            }
        }).then((result) => {
            if (result.isConfirmed) {
                showLoader();
                $.ajax({
                    url: '../../acoes/pendente_etapa.php',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        id_procedimento: procedimentoId,
                        id_user: $('#idUser').val(),
                        id_municipio: $('#idMunicipio').val(),
                        etapa: etapaId,
                        descricao: result.value
                    },
                    success: handleResponse,
                    error: () => handleError('Erro ao marcar a etapa como pendente. Tente novamente.')
                });
            }
        });
    });

    $('.enviarAnalise').click(function () {
        const etapaId = $(this).data('etapa');

        if (!etapaId || !procedimentoId) {
            Swal.fire('Erro!', 'Dados insuficientes para enviar a etapa para análise.', 'error');
            return;
        }

        Swal.fire({
            title: 'Confirmação',
            text: 'Você deseja enviar esta etapa para análise? (Justificativa opcional)',
            input: 'textarea',
            inputPlaceholder: 'Digite sua justificativa aqui (opcional)...',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Sim, enviar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                const justificativa = result.value || '';
                showLoader();
                $.ajax({
                    url: '../../acoes/analise_etapa.php',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        id_procedimento: procedimentoId,
                        id_user: $('#idUser').val(),
                        id_municipio: $('#idMunicipio').val(),
                        etapa: etapaId,
                        justificativa: justificativa
                    },
                    success: handleResponse,
                    error: () => handleError('Erro ao enviar a etapa para análise. Tente novamente.')
                });
            }
        });
    });
});


/* Histórico */
$(document).ready(function () {
    $('#btnHistorico').click(function () {
        const procedimentoId = $('#id').val();
        const historicoTimeline = $('#historicoTimeline');
        historicoTimeline.empty();

        $.ajax({
            url: '../../acoes/historico_processo.php',
            type: 'POST',
            dataType: 'json',
            data: { id_procedimento: procedimentoId },
            beforeSend: function () {
                $('#content').css("opacity", ".5"); // Reduz a opacidade do conteúdo
            },
            success: function (response) {
                if (response.status === 'success') {
                    const historico = response.data;

                    if (historico.length === 0) {
                        historicoTimeline.append(`
                            <li class="text-muted">
                                <h6>Sem histórico registrado</h6>
                            </li>
                        `);
                    } else {
                        historico.forEach(item => {
                            const formattedDate = new Date(item.h_date).toLocaleString('pt-BR', {
                                day: '2-digit',
                                month: '2-digit',
                                year: 'numeric',
                                hour: '2-digit',
                                minute: '2-digit'
                            });

                            // Adiciona uma classe e legenda para o último item
                            const lastClass = item.is_last ? 'last-item' : '';
                            const lastLegend = item.is_last
                                ? '<span class="badge badge-success ml-2">Último Histórico</span>'
                                : '';

                            historicoTimeline.append(`
                                <li class="timeline-item ${lastClass}">
                                    <span class="timeline-marker ${item.status_class}"></span>
                                    <div class="timeline-content">
                                        <span class="timeline-date">
                                            <i class="fa fa-clock"></i> ${formattedDate} - 
                                            <i class="fa fa-user"></i> ${item.user_name || ''}
                                        </span>
                                        <h6 class="timeline-title">${item.h_name} ${lastLegend}</h6>
                                        <p class="timeline-description">${item.h_justificativa || ''}</p>
                                    </div>
                                </li>
                            `);
                        });
                    }
                    $('#content').css("opacity", "");
                    $('#historicoModal').modal('show');
                } else {
                    $('#content').css("opacity", "");
                    Swal.fire({
                        title: response.tittle,
                        html: response.message,
                        icon: response.icon
                    });
                }
            },
            error: function () {
                Swal.fire('Erro!', 'Não foi possível carregar o histórico.', 'error');
            }
        });
    });
});


