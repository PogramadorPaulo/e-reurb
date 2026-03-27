   <!-- Style.css -->
   <link rel="stylesheet" type="text/css" href="<?php echo BASE_URL; ?>assets/tema/css/style-processos.css">
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

                   <div class="card">
                       <ul class="nav nav-tabs md-tabs" role="tablist" id="etapasTabs">
                           <?php
                            $etapaLiberada = true; // Primeira etapa começa liberada

                            foreach ($etapas as $index => $item):
                                // Define o estado de habilitação para a aba
                                if ($index > 0 && $etapas[$index - 1]['status_nome'] !== 'Concluída') {
                                    $etapaLiberada = false;
                                }

                                $disabledClass = $etapaLiberada ? '' : 'disabled';
                                $activeClass = ($index === 0) ? 'active' : '';
                            ?>
                               <li class="nav-item">
                                   <a
                                       class="nav-link <?php echo "$activeClass $disabledClass"; ?>"
                                       data-toggle="tab"
                                       href="#etapa_<?php echo $item['etapa_id']; ?>"
                                       role="tab"
                                       aria-controls="etapa_<?php echo $item['etapa_id']; ?>"
                                       aria-disabled="<?php echo $etapaLiberada ? 'false' : 'true'; ?>">
                                       <div style="font-size: 13px;"><?php echo htmlspecialchars($item['etapa_nome']); ?></div>
                                       <label style="font-size: 9px;"><?php echo htmlspecialchars($item['etapa_descricao']); ?></label>
                                       <br>
                                       <!-- Adicionando a legenda -->
                                       <?php if ($item['status_nome'] === 'Concluída'): ?>
                                           <span class="badge badge-success" style="padding: 4px; font-size: 9px;">
                                            <i class="fa fa-check-circle"></i> Concluída</span>
                                       <?php elseif ($item['status_nome'] === 'Em andamento'): ?>
                                           <span class="badge badge-primary" style="padding: 4px; font-size: 9px;">Em andamento</span>
                                       <?php elseif ($item['status_nome'] === 'Pendente'): ?>
                                           <span class="badge badge-warning" style="padding: 4px; font-size: 9px;">
                                            <i class="fa fa-exclamation-circle"></i> Pendente</span>
                                       <?php elseif ($item['status_nome'] === 'Bloqueada'): ?>
                                           <span class="badge badge-danger" style="padding: 4px; font-size: 9px;">Bloqueada</span>
                                       <?php elseif ($item['status_nome'] === 'Em análise'): ?>
                                           <span class="badge badge-info" style="padding: 4px; font-size: 9px;">
                                            <i class="fa fa-hourglass-half"></i>Em análise</span>
                                       <?php elseif ($item['status_nome'] === 'Aberto'): ?>
                                           <span class="badge badge-secondary" style="padding: 4px; font-size: 9px;">Aberto</span>
                                       <?php else: ?>
                                           <span class="badge badge-light" style="padding: 4px; font-size: 9px;">
                                            <i class="fa fa-hourglass-half"></i>Aguardando etapa concluir</span>
                                       <?php endif; ?>
                                   </a>
                                   <div class="slide"></div>
                               </li>
                           <?php endforeach; ?>
                       </ul>

                       <div class="tab-content card-block" id="etapasContent">
                           <?php foreach ($etapas as $index => $item): ?>
                               <div
                                   class="tab-pane fade <?php echo ($index === 0) ? 'show active' : ''; ?>" id="etapa_<?php echo $item['etapa_id']; ?>"
                                   role="tabpanel"
                                   aria-labelledby="etapa_<?php echo $item['etapa_id']; ?>_tab">
                                   <!-- Conteúdo específico para cada aba -->
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