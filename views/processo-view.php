   <!-- Style.css -->
   <link rel="stylesheet" type="text/css" href="<?php echo BASE_URL; ?>assets/tema/css/style-processos.css">
   <script type="text/javascript" src="<?php echo BASE_URL; ?>assets/js/processos/etapas-tabs.js"></script>
   <!-- Page-header end -->
   <div class="pcoded-inner-content">
       <!-- Main-body start -->
       <div class="main-body" id="content">
           <div class="page-wrapper">
               <!-- Page-body start -->
               <div class="page-body">
                   <div class="d-flex justify-content-center mb-3">
                       <div class="spinner-border" role="status" id="loader">
                           <i class="fa fa-circle-o-notch fa-spin fa-3x fa-fw"></i>
                       </div>
                   </div>
                   <input type="hidden" name="idMunicipio" id="idMunicipio" value="<?php echo $idMunicipio; ?>">
                   <!-- Tab variant tab card start -->
                   <div class="card p-3">
                       <div class="d-flex justify-content-between align-items-center">
                           <div>
                               <h6 class="mb-0">
                                   Processo: <span class="font-weight-bold"><?php echo $procedimento[0]['numero_procedimento']; ?></span><br>
                                   Código: <span class="font-weight-bold"><?php echo $id; ?></span>
                               </h6>
                           </div>
                           <button id="btnHistorico" class="btn btn-light btn-sm">
                               <i class="fa fa-history"></i> Histórico do Processo
                           </button>
                       </div>
                   </div>

                   <div class="card etapas-tabs-card">
                       <div class="etapas-tabs-header">
                           <span class="etapas-tabs-header-label">Etapas do procedimento</span>
                       </div>
                       <div class="etapas-tabs-scroll-wrap">
                       <ul class="nav nav-tabs md-tabs etapas-tabs-nav etapas-tabs-scroll flex-nowrap" role="tablist" id="etapasTabs">
                           <?php
                            $etapaLiberada = true; // Primeira etapa começa liberada

                            foreach ($etapas as $index => $item):
                                // Define o estado de habilitação para a aba
                                if ($index > 0 && $etapas[$index - 1]['status_nome'] !== 'Concluída') {
                                    $etapaLiberada = false;
                                }

                                $disabledClass = $etapaLiberada ? '' : 'disabled';
                                $activeClass = ($index === 0) ? 'active' : '';
                                $descRaw = isset($item['etapa_descricao']) ? trim((string) $item['etapa_descricao']) : '';
                                $descLinha = $descRaw !== '' ? preg_replace('/\s+/u', ' ', $descRaw) : '';
                                $titleTooltip = trim($item['etapa_nome'] . ($descLinha !== '' ? ' — ' . $descLinha : ''));
                            ?>
                               <li class="nav-item etapas-tabs-item" role="presentation">
                                   <a
                                       id="etapa_<?php echo (int) $item['etapa_id']; ?>_tab"
                                       class="nav-link etapas-tab-link <?php echo "$activeClass $disabledClass"; ?>"
                                       data-toggle="tab"
                                       data-tab-num="<?php echo (int) $item['etapa_id']; ?>"
                                       href="#etapa_<?php echo $item['etapa_id']; ?>"
                                       role="tab"
                                       title="<?php echo htmlspecialchars($titleTooltip, ENT_QUOTES, 'UTF-8'); ?>"
                                       aria-controls="etapa_<?php echo $item['etapa_id']; ?>"
                                       aria-selected="<?php echo ($index === 0) ? 'true' : 'false'; ?>"
                                       tabindex="<?php echo ($index === 0) ? '0' : '-1'; ?>"
                                       aria-disabled="<?php echo $etapaLiberada ? 'false' : 'true'; ?>">
                                       <span class="etapa-tab-main">
                                           <span class="etapa-tab-title"><?php echo htmlspecialchars($item['etapa_nome']); ?></span>
                                           <?php if ($descLinha !== '') : ?>
                                           <span class="etapa-tab-desc"><?php echo htmlspecialchars($descLinha); ?></span>
                                           <?php endif; ?>
                                       </span>
                                       <span class="etapa-tab-status">
                                       <?php if ($item['status_nome'] === 'Concluída'): ?>
                                           <span class="badge badge-etapa badge-success"><i class="fa fa-check-circle" aria-hidden="true"></i> Concluída</span>
                                       <?php elseif ($item['status_nome'] === 'Em andamento'): ?>
                                           <span class="badge badge-etapa badge-primary">Em andamento</span>
                                       <?php elseif ($item['status_nome'] === 'Pendente'): ?>
                                           <span class="badge badge-etapa badge-warning"><i class="fa fa-exclamation-circle" aria-hidden="true"></i> Pendente</span>
                                       <?php elseif ($item['status_nome'] === 'Bloqueada'): ?>
                                           <span class="badge badge-etapa badge-danger">Bloqueada</span>
                                       <?php elseif ($item['status_nome'] === 'Em análise'): ?>
                                           <span class="badge badge-etapa badge-info"><i class="fa fa-hourglass-half" aria-hidden="true"></i> Em análise</span>
                                       <?php elseif ($item['status_nome'] === 'Aberto'): ?>
                                           <span class="badge badge-etapa badge-secondary">Aberto</span>
                                       <?php else: ?>
                                           <span class="badge badge-etapa badge-light"><i class="fa fa-hourglass-half" aria-hidden="true"></i> Aguardando</span>
                                       <?php endif; ?>
                                       </span>
                                   </a>
                                   <div class="slide"></div>
                               </li>
                           <?php endforeach; ?>
                       </ul>
                       </div>

                       <div class="tab-content card-block" id="etapasContent">
                           <?php foreach ($etapas as $index => $item): ?>
                               <div
                                   class="tab-pane fade etapa-tab-pane <?php echo ($index === 0) ? 'show active' : ''; ?>" id="etapa_<?php echo $item['etapa_id']; ?>"
                                   role="tabpanel"
                                   data-etapa-id="<?php echo (int) $item['etapa_id']; ?>"
                                   aria-labelledby="etapa_<?php echo $item['etapa_id']; ?>_tab">
                                   <div class="etapa-tab-loader" aria-hidden="true" aria-busy="false">
                                       <div class="etapa-tab-loader-inner">
                                           <span class="spinner-border text-primary" role="status"></span>
                                           <span class="etapa-tab-loader-text">Carregando etapa…</span>
                                       </div>
                                   </div>
                                   <div class="etapa-tab-body">
                                   <?php
                                    switch (mb_strtolower($item['etapa_slug'], 'UTF-8')) {
                                        case '1etapa':
                                            require_once('tabs/tab_1etapa.php');
                                            break;
                                        case '2etapa':
                                            require_once('tabs/tab_2etapa.php');
                                            break;
                                        case '3etapa':
                                            require_once('tabs/tab_3etapa.php');
                                            break;
                                        case '4etapa':
                                            require_once('tabs/tab_4etapa.php');
                                            break;
                                        case '5etapa':
                                            require_once('tabs/tab_5etapa.php');
                                            break;
                                        case '6etapa':
                                            require_once('tabs/tab_6etapa.php');
                                            break;
                                        case '7etapa':
                                            require_once('tabs/tab_7etapa.php');
                                            break;
                                        default:
                                            echo "<p>Conteúdo não disponível para esta etapa.</p>";
                                    }
                                    ?>
                                   </div>
                               </div>
                           <?php endforeach; ?>
                       </div>
                   </div>
                   <!-- Tab variant tab card end -->
               </div>
           </div>
       </div>
   </div>

   <!-- Modal -->
   <div class="modal fade" id="historicoModal" tabindex="-1" aria-labelledby="historicoModalLabel" aria-hidden="true">
       <div class="modal-dialog modal-lg">
           <div class="modal-content">
               <div class="modal-header">
                   <h5 class="modal-title" id="historicoModalLabel">
                       <i class="fa fa-history"></i> Histórico do Processo
                   </h5>
                   <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                       <span aria-hidden="true">&times;</span>
                   </button>
               </div>
               <div class="modal-body">
                   <div class="timeline">
                       <ul id="historicoTimeline" class="timeline-list">
                           <!-- Os itens do histórico serão adicionados dinamicamente -->
                       </ul>
                   </div>
               </div>
           </div>
       </div>
   </div>

   <script type="text/javascript" src="<?php echo BASE_URL; ?>acoes/js.js"></script>