<div class="modal fade modal-xl" id="loja-modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <form action="../controladores/loja/criar-loja.php" class="modal-content" method="POST" enctype="multipart/form-data">
            <div class="modal-header fs-6">
                <span class="me-1"><i class="bi bi-shop"></i></span>&nbsp;Configurações da loja
            </div>
            <div class="modal-body p-4">

                <!-- 📋 DADOS CADASTRAIS DA LOJA -->
                <h5 class="mb-3">Dados Cadastrais</h5>
                <div class="row g-3 mb-4">
                    <div class="col-6">
                        <label class="form-label">Nome da loja<sup class="text-danger">*</sup></label>
                        <input type="text" class="form-control shadow-sm" name="nome" required>
                    </div>
                    <div class="col-6">
                        <label class="form-label">Razão Social</label>
                        <input type="text" class="form-control shadow-sm" name="razao_social">
                    </div>
                    <div class="col-6">
                        <label class="form-label">CNPJ</label>
                        <input type="text" class="form-control shadow-sm" name="cnpj" pattern="\d{2}\.\d{3}\.\d{3}/\d{4}-\d{2}">
                    </div>
                    <div class="col-6">
                        <label class="form-label">Inscrição Estadual</label>
                        <input type="text" class="form-control shadow-sm" name="inscricao_estadual">
                    </div>
                </div>

                <!-- 📍 DADOS DE LOCALIZAÇÃO -->
                <h5 class="mb-3">Localização</h5>
                <div class="row g-3 mb-4">
                    <!-- Logradouro removido conforme solicitado -->
                    <div class="col-4">
                        <label class="form-label">Número</label>
                        <input type="text" class="form-control shadow-sm" name="numero">
                    </div>
                    <div class="col-4">
                        <label class="form-label">CEP</label>
                        <input type="text" class="form-control shadow-sm" name="cep" pattern="\d{5}-\d{3}">
                    </div>
                    <div class="col-4">
                        <label class="form-label">Bairro</label>
                        <input type="text" class="form-control shadow-sm" name="bairro">
                    </div>
                    <div class="col-6">
                        <label class="form-label">Estado (UF)</label>
                        <select class="form-select shadow-sm" name="estado" id="loja-estado">
                            <option value="">Selecione um estado...</option>
                        </select>
                    </div>
                    <div class="col-6">
                        <label class="form-label">Cidade</label>
                        <select class="form-select shadow-sm" name="cidade" id="loja-cidade">
                            <option value="">Selecione o estado antes...</option>
                        </select>
                    </div>
                </div>

                <!-- 📞 CONTATO COMERCIAL -->
                <h5 class="mb-3">Contato Comercial</h5>
                <div class="row g-3 mb-4">
                    <div class="col-6">
                        <label class="form-label">Telefone fixo</label>
                        <input type="text" class="form-control shadow-sm telefone-mask" name="telefone_fixo">
                    </div>
                    <div class="col-6">
                        <label class="form-label">WhatsApp comercial<sup class="text-danger">*</sup></label>
                        <input type="text" class="form-control shadow-sm telefone-mask" name="whatsapp" required>
                    </div>
                    <div class="col-6">
                        <label class="form-label">E-mail corporativo<sup class="text-danger">*</sup></label>
                        <input type="email" class="form-control shadow-sm" name="email_corporativo" required>
                    </div>
                    <div class="col-6">
                        <label class="form-label">Site</label>
                        <input type="url" class="form-control shadow-sm" name="site">
                    </div>
                    <div class="col-6">
                        <label class="form-label">Instagram</label>
                        <input type="text" class="form-control shadow-sm" name="instagram">
                    </div>
                    <div class="col-6">
                        <label class="form-label">Facebook</label>
                        <input type="text" class="form-control shadow-sm" name="facebook">
                    </div>
                </div>

                <!-- 🖼️ IDENTIDADE VISUAL -->
                <h5 class="mb-3">Identidade Visual</h5>
                <div class="row g-3 mb-4">
                    <div class="col-12 d-flex align-items-center gap-4 flex-wrap">
                        <div class="d-flex flex-column align-items-center">
                            <div class="position-relative">
                                <img id="logo-preview" src="../img/usuarios/avatares/user.png" alt="Logo da loja" class="object-fit-cover rounded-circle border shadow-sm mb-2" width="128" height="128">
                            </div>
                            <small class="text-muted">Prévia da logo</small>
                        </div>
                        <div class="ms-auto d-flex flex-column align-items-end gap-2">
                            <label for="logo" class="btn bg-body-secondary shadow-sm">
                                <i class="bi bi-upload me-1"></i> Escolher logo
                            </label>
                            <input type="file" class="d-none" id="logo" name="logo" accept="image/*">
                            <small class="text-muted">Formatos aceitos: PNG, JPG, até 10MB</small>
                        </div>
                    </div>
                    <div class="col-12 d-flex align-items-center gap-4 flex-wrap mt-3">
                        <div class="d-flex flex-column align-items-center" style="width:100%;max-width:50%;">
                            <div class="ratio w-100 border rounded shadow-sm mb-2" style="--bs-aspect-ratio: 20%;">
                                <img class="object-fit-cover img-fluid" id="capa-preview" src="../img/banner/capa-template.png" alt="Capa do perfil">
                            </div>
                            <small class="text-muted">Prévia da capa (1:5)</small>
                        </div>
                        <div class="ms-auto d-flex flex-column align-items-end gap-2">
                            <label for="capa" class="btn bg-body-secondary shadow-sm">
                                <i class="bi bi-upload me-1"></i> Escolher capa
                            </label>
                            <input type="file" class="d-none" id="capa" name="capa" accept="image/*">
                            <small class="text-muted">Formatos aceitos: PNG, JPG, até 16MB</small>
                        </div>
                    </div>
                    <!-- Horários avançados panel moved to Funcionamento section -->
                    <div class="col-12">
                        <label class="form-label">Descrição da loja<sup class="text-danger">*</sup></label>
                        <textarea class="form-control shadow-sm" name="descricao_loja" rows="2" required maxlength="1000"></textarea>
                    </div>
                </div>

                <!-- ⏰ FUNCIONAMENTO -->
                <h5 class="mb-3">Funcionamento</h5>
                <div class="row g-3 mb-4">
                        <div class="col-6">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <label class="form-label mb-0">Horário de atendimento<sup class="text-danger">*</sup></label>
                                <div class="form-check mb-0">
                                    <input class="form-check-input" type="checkbox" id="horarios-avancado" name="horarios_avancado">
                                    <label class="form-check-label small" for="horarios-avancado">Horários avançados</label>
                                </div>
                            </div>
                            <div class="d-flex gap-2 align-items-center simple-hours">
                                <div class="flex-grow-1">
                                    <label for="hora_abre" class="form-label mb-1">Abre</label>
                                    <input required type="time" class="form-control shadow-sm" name="hora_abre" id="hora_abre" step="600">
                                </div>
                                <div class="flex-grow-1">
                                    <label for="hora_fecha" class="form-label mb-1">Fecha</label>
                                    <input required type="time" class="form-control shadow-sm" name="hora_fecha" id="hora_fecha" step="600">
                                </div>
                            </div>
                            <!-- note about 10-minute interval removed as requested -->

                            <div id="horarios-avancado-panel" class="mt-3 d-none">
                                <div class="table-responsive">
                                    <table class="table table-sm table-bordered mb-0">
                                        <thead>
                                            <tr>
                                                <th class="ps-2">Aberto?</th>
                                                <th class="ps-2">Dia</th>
                                                <th class="ps-2">Abre</th>
                                                <th class="ps-2">Fecha</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $dias = ['Domingo','Segunda','Terça','Quarta','Quinta','Sexta','Sábado'];
                                            for ($i=0;$i<7;$i++): ?>
                                            <tr>
                                                <td class="ps-2 text-center">
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox" name="horarios[<?= $i ?>][aberto]" value="1" id="hor-aberto-<?= $i ?>">
                                                    </div>
                                                </td>
                                                <td class="ps-2"><?= $dias[$i] ?></td>
                                                <td><input type="time" class="form-control form-control-sm" name="horarios[<?= $i ?>][abre]"></td>
                                                <td><input type="time" class="form-control form-control-sm" name="horarios[<?= $i ?>][fecha]"></td>
                                            </tr>
                                            <?php endfor; ?>
                                        </tbody>
                                    </table>
                                    <!-- removed 'leave blank to indicate closed' note per request -->
                                </div>
                            </div>
                        </div>
                    <div class="col-6" id="dias-semana-block">
                        <label class="form-label mb-4">Dias da semana<sup class="text-danger">*</sup></label>
                        <div class="d-flex flex-wrap justify-content-between gap-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="dias_funcionamento[]" value="1" id="dia-dom">
                                <label class="form-check-label" for="dia-dom">Domingo</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="dias_funcionamento[]" value="2" id="dia-seg">
                                <label class="form-check-label" for="dia-seg">Segunda</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="dias_funcionamento[]" value="3" id="dia-ter">
                                <label class="form-check-label" for="dia-ter">Terça</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="dias_funcionamento[]" value="4" id="dia-qua">
                                <label class="form-check-label" for="dia-qua">Quarta</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="dias_funcionamento[]" value="5" id="dia-qui">
                                <label class="form-check-label" for="dia-qui">Quinta</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="dias_funcionamento[]" value="6" id="dia-sex">
                                <label class="form-check-label" for="dia-sex">Sexta</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="dias_funcionamento[]" value="7" id="dia-sab">
                                <label class="form-check-label" for="dia-sab">Sábado</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn bg-body-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-dark">Salvar</button>
            </div>
        </form>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Logo preview
    const logoInput = document.getElementById('logo');
    const logoPreview = document.getElementById('logo-preview');
    if (logoInput && logoPreview) {
        logoInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(ev) {
                    logoPreview.src = ev.target.result;
                };
                reader.readAsDataURL(file);
            } else {
                logoPreview.src = '../img/user.png';
            }
        });
    }
    // IBGE: popular selects de estado/municipio para criação de loja
    const ibgeEstadosUrl = 'https://servicodados.ibge.gov.br/api/v1/localidades/estados';
    const lojaEstado = document.getElementById('loja-estado');
    const lojaCidade = document.getElementById('loja-cidade');

    if (lojaEstado && lojaCidade) {
        // popular estados
        fetch(ibgeEstadosUrl).then(res => res.json()).then(estados => {
            estados.sort((a,b) => a.nome.localeCompare(b.nome));
            lojaEstado.innerHTML = '<option value="">Selecione um estado...</option>';
            estados.forEach(e => {
                const opt = document.createElement('option');
                opt.value = e.sigla;
                opt.dataset.id = e.id;
                opt.textContent = `${e.nome} (${e.sigla})`;
                lojaEstado.appendChild(opt);
            });
        }).catch(err => { console.error('Erro ao carregar estados IBGE:', err); });

        lojaEstado.addEventListener('change', function() {
            const estadoId = this.options[this.selectedIndex].dataset.id;
            if (!estadoId) {
                lojaCidade.innerHTML = '<option value="">Selecione um município...</option>';
                return;
            }
            lojaCidade.innerHTML = '<option>Carregando municípios...</option>';
            fetch(`https://servicodados.ibge.gov.br/api/v1/localidades/estados/${estadoId}/municipios`).then(r => r.json()).then(list => {
                list.sort((a,b) => a.nome.localeCompare(b.nome));
                lojaCidade.innerHTML = '<option value="">Selecione um município...</option>';
                list.forEach(m => {
                    const o = document.createElement('option');
                    o.value = m.nome;
                    o.textContent = m.nome;
                    lojaCidade.appendChild(o);
                });
            }).catch(err => { console.error('Erro ao carregar municípios IBGE:', err); lojaCidade.innerHTML = '<option value="">Erro ao carregar municípios</option>'; });
        });
    }
    // Capa preview
    const capaInput = document.getElementById('capa');
    const capaPreview = document.getElementById('capa-preview');
    if (capaInput && capaPreview) {
        capaInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(ev) {
                    capaPreview.src = ev.target.result;
                };
                reader.readAsDataURL(file);
            } else {
                capaPreview.src = '../img/banner/capa-template.png';
            }
        });
    }
    // Horários avançados toggle: show per-day panel and hide simple inputs
    const horariosCheckbox = document.getElementById('horarios-avancado');
    const horariosPanel = document.getElementById('horarios-avancado-panel');
    const simpleHours = document.querySelector('.simple-hours');
    const horaAbre = document.getElementById('hora_abre');
    const horaFecha = document.getElementById('hora_fecha');
    function applyHorarioMode() {
        if (!horariosCheckbox) return;
        const checked = horariosCheckbox.checked;
        if (horariosPanel) {
            horariosPanel.classList.toggle('d-none', !checked);
        }
        if (simpleHours) {
            simpleHours.classList.toggle('d-none', checked);
        }
        if (horaAbre) {
            horaAbre.required = !checked;
            horaAbre.disabled = checked;
        }
        if (horaFecha) {
            horaFecha.required = !checked;
            horaFecha.disabled = checked;
        }
        // toggle dias-of-week block (hide/disable inputs when advanced mode)
        const diasBlock = document.getElementById('dias-semana-block');
        if (diasBlock) {
            diasBlock.classList.toggle('d-none', checked);
            diasBlock.querySelectorAll('input').forEach(function(inp) { inp.disabled = checked; });
        }
    }
    if (horariosCheckbox) {
        horariosCheckbox.addEventListener('change', applyHorarioMode);
        // apply initial state
        applyHorarioMode();
    }
});
</script>