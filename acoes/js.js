
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
            title: jsonResponseTitle(response),
            html: response.message,
            icon: response.icon
        }).then(() => {
            if (response.status === 'success') {
                if (window.EtapasTabs && typeof EtapasTabs.reloadPreservingTab === 'function') {
                    EtapasTabs.reloadPreservingTab();
                } else {
                    var t = new URLSearchParams(window.location.search).get('tab') || '1';
                    window.location.href = window.location.pathname + '?tab=' + encodeURIComponent(t);
                }
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
                        title: jsonResponseTitle(response),
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

