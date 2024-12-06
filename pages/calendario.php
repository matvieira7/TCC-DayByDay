<?php

$idUsuario = $_SESSION['idUsuario']; // Assumindo que você tem o ID do usuário na sessão

// Obter o valor de nota_tuto do banco de dados
$query = "SELECT calendar_tuto FROM usuarios WHERE id = '$idUsuario'";
$result = mysqli_query($conexao, $query);

$showTutorial = false; // Inicialmente, não exibe o tutorial automaticamente

if ($result && $row = mysqli_fetch_assoc($result)) {
    // Se nota_tuto for "y", exibe o tutorial automaticamente
    $showTutorial = ($row['calendar_tuto'] === 'y');
}

// Processar a requisição para dispensar o tutorial
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'dispensar_tutorial') {
    $stmt = $conn->prepare("CALL AtualizarCalendarTutoParaNull(?)");
    $stmt->bind_param("i", $idUsuario);
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $stmt->error]);
    }
    $stmt->close();
    $conn->close();
    exit();
}

?>
<style>

.row {
    margin-left: 0;
}

    .modal-content {
        position: relative;
    }

    .overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 1500;
        display: flex;
        align-items: center;
        justify-content: center;
    }


    /* Botões de controle do tutorial */
    .next-button,
    .back-button,
    .close-button {
        position: absolute;
        border: none;
        cursor: pointer;
        z-index: 1600;
        font-size: 24px;
        /* Ajusta o tamanho da seta */
        width: 50px;
        /* Tamanho da bolinha */
        height: 50px;
        /* Tamanho da bolinha */
        border-radius: 50%;
        /* Forma circular */
        display: flex;
        align-items: center;
        justify-content: center;
        color: #FFFFFF;
        /* Cor da seta */
        background-color: var(--purple);
        /* Cor de fundo roxa */
    }

    /* Estilo do botão Avançar (seta para a direita) */
    .next-button {
        bottom: 350px;
        right: 20px;
    }

    /* Estilo do botão Retornar (seta para a esquerda) */
    .back-button {
        bottom: 350px;
        left: 30px;
        display: none;
    }

    /* Estilo do botão Fechar */
    .close-button {
        bottom: 20px;
        left: 50%;
        transform: translateX(-50%);
        /* Centraliza horizontalmente */
        color: #ffffff;
        /* Cor cinza apagada */
        background: none;
        /* Sem fundo */

        font-size: 14px;
    }

    /* Alinhar a imagem à esquerda e o texto à direita */
    #tutorialOverlay .content {
        display: flex;
        align-items: center;
        /* Alinha verticalmente no centro */
        justify-content: flex-start;
        /* Alinha à esquerda */
    }

    #tutorialOverlay .content img {
        width: 50%;
        margin-right: 10px;
    }

    #tutorialOverlay .content p {
        flex-grow: 1;
    }

    /* Estilo para o fundo branco e layout geral */
    .overlay .content-wrapper {
        background-color: #ffffff;
        /* Fundo branco */
        padding: 20px;
        /* Espaçamento interno */
        box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.1);
        /* Sombra para destaque */
        max-width: 700px;
        /* Largura máxima para o conteúdo */
        margin: auto;
        /* Centraliza o conteúdo */
    }

    /* Estilo para o título */
    .content-header h2 {
        font-size: 24px;
        font-weight: bold;
        margin-bottom: 15px;
        color: var(--purple);
        text-align: center;
        /* Centraliza o título */
    }

    /* Estilo para imagem e texto lado a lado */
    .content {
        display: flex;
        align-items: center;
        justify-content: flex-start;
    }

    .content img {
        width: 50%;
    }

    .content p {
        flex-grow: 1;
        font-size: 16px;
        color: #555;
    }

    .newOverlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 1500;
        display: flex;
        align-items: center;
        justify-content: center;

        /* Máscara com borda roxa e círculo central transparente */

    }

    /* Estilo para o fundo branco e layout geral */
    .newOverlay .content-wrapper {
        background-color: #ffffff;
        /* Fundo branco */
        padding: 20px;
        /* Espaçamento interno */
        box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.1);
        /* Sombra para destaque */
        max-width: 700px;
        /* Largura máxima para o conteúdo */
        margin: auto;
        /* Centraliza o conteúdo */
    }

    /* Estilo da bolinha */
    #helpButtonNew {
        position: fixed;
        bottom: 20px;
        right: 20px;
        width: 45px;
        height: 45px;
        background-color: var(--purple);
        /* Cor roxa */
        border-radius: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
        box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        cursor: pointer;
        z-index: 100;
    }

    #helpButtonNew:hover {
        background-color: var(--purple);
        /* Tom mais escuro de roxo */
    }

    #helpButtonNew .material-symbols-outlined {
        font-size: 35px;
        color: white;
    }

    /* Alinhar a imagem à esquerda e o texto à direita */
    #newTutorialOverlay .content {
        display: flex;
        align-items: center;
        /* Alinha verticalmente no centro */
        justify-content: flex-start;
        /* Alinha à esquerda */
    }

    #newTutorialOverlay .content img {
        width: 50%;
        margin-right: 10px;
    }

    #newTutorialOverlay .content p {
        flex-grow: 1;
    }

    .btn-confirm {
        background-color: var(--purple);
        color: white;

    }

    .btn-cancel {
        background-color: var(--background);
        color: var(--text-color);
    }

    .btn-confirm:hover {
        background-color: var(--purple);
        color: white;
    }

    .btn-cancel:hover {
        background-color: var(--background);
        color: var(--text-color);
    }

    .modal-confirm {
        display: none;
        position: fixed;
        top: 70%;
        left: 50%;
        color: var(--text-color);
        transform: translate(-50%, -50%);
        padding: 20px;
        background-color: var(--white);
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        z-index: 1000;
    }
</style>

<body>

    <!-- Botão de Ajuda -->
    <div id="helpButtonNew">
        <span class="material-symbols-outlined"><ion-icon name="help-circle-outline"></ion-icon></span>
    </div>

    <!-- Novo Tutorial -->
    <div class="newOverlay" id="newTutorialOverlay" style="display: none;">
        <button class="back-button" id="backButtonNew" onclick="prevStepNew()">
            <span class="material-symbols-outlined">arrow_back_ios</span>
        </button>

        <div class="content-wrapper">
            <div class="content-header">
                <h2 id="stepTitleNew"></h2>
            </div>
            <div class="content">
                <img id="stepImageNew" src="" alt="Tutorial Step" />
                <p id="stepTextNew"></p>
            </div>
        </div>

        <button class="next-button" onclick="nextStepNew()">
            <span class="material-symbols-outlined">arrow_forward_ios</span>
        </button>
        <a href="#" class="close-button" onclick="closeNewTutorial()">Dispensar</a>
    </div>

    <script>
        const newSteps = [
            {
                selector: '#calendar',
                image: '../img/tutorial/calendario.gif',
                text: 'Nas setas acima do calendário você pode avançar e retroceder.\n' +
                    'Quando você está na tela dos meses, os meses serão alterados, e quando na tela dos anos, os anos serão alterados.',
                title: 'Avançar e Retroceder ',
                size: 25
            },
            {
                selector: '#calendar',
                image: '../img/tutorial/evento.gif',
                text: 'Ao clicar em uma data, você poderá criar um evento nesse dia.\n' +
                    'Basta preencher o título do evento, descrição e selecionar uma cor.\n' +
                    '\n' +
                    '(A descrição é opcional, você pode deixá - la em branco se preferir)',
                title: 'Criar Eventos',
                size: 30
            },
            {
                selector: '#calendar',
                image: '../img/tutorial/edicao.gif',
                text: 'Ao clicar em um evento criado, você poderá editar e excluir esse evento pelos ícones no canto inferior direito.\n' +
                    '\n' +
                    'O pincel é responsável pela edição, basta clicar nele, alterar as informações anteriormente preenchidas e concluir a alteração clicando no ícone roxo no canto inferior direito.\n' +
                    '\n' +
                    'O lixo é responsável pela exclusão, ao clicar nele seu evento será excluído.',
                title: 'Edição e Exclusão',
                size: 30
            },
        ];

        let newCurrentStep = 0;

        // Atualiza a posição da máscara no novo tutorial
        function updateMaskPositionNew(selector) {
            const element = document.querySelector(selector);
            if (element) {
                const rect = element.getBoundingClientRect();
                const x = rect.left + rect.width / 2;
                const y = rect.top + rect.height / 2;
                const size = newSteps[newCurrentStep].size;

                const overlay = document.getElementById('newTutorialOverlay');
                overlay.style.maskImage = `radial-gradient(circle ${size}px at ${x}px ${y}px, transparent 0%, black 0%)`;
                overlay.style.webkitMaskImage = `radial-gradient(circle ${size}px at ${x}px ${y}px, transparent 0%, black 0%)`;
            }
        }

        // Atualiza o conteúdo do novo tutorial
        function updateContentNew() {
            const step = newSteps[newCurrentStep];
            document.getElementById('stepImageNew').src = step.image;
            document.getElementById('stepTextNew').innerText = step.text;
            document.getElementById('stepTitleNew').innerText = step.title;

            const backButton = document.getElementById('backButtonNew');
            const nextButton = document.querySelector('#newTutorialOverlay .next-button');

            backButton.style.display = newCurrentStep === 0 ? 'none' : 'block';
            nextButton.style.display = newCurrentStep === newSteps.length - 1 ? 'none' : 'block';
        }

        // Avançar para o próximo passo no novo tutorial
        function nextStepNew() {
            if (newCurrentStep < newSteps.length - 1) {
                newCurrentStep++;
                updateMaskPositionNew(newSteps[newCurrentStep].selector);
                updateContentNew();
            } else {
                closeNewTutorial();
            }
        }

        // Voltar para o passo anterior no novo tutorial
        function prevStepNew() {
            if (newCurrentStep > 0) {
                newCurrentStep--;
                updateMaskPositionNew(newSteps[newCurrentStep].selector);
                updateContentNew();
            }
        }

        // Fechar o novo tutorial
        function closeNewTutorial() {
            document.getElementById('newTutorialOverlay').style.display = 'none';
        }

        // Iniciar o novo tutorial
        function startNewTutorial() {
            setTimeout(() => {
                updateMaskPositionNew(newSteps[newCurrentStep].selector);
                updateContentNew();
            }, 100);
        }

        // Evento para abrir o novo tutorial ao clicar no botão de ajuda
        document.getElementById('helpButtonNew').addEventListener('click', () => {
            document.getElementById('newTutorialOverlay').style.display = 'flex';
            newCurrentStep = 0; // Reinicia o novo tutorial
            startNewTutorial();
        });

        // Atualizar a posição da máscara do novo tutorial ao redimensionar a janela
        window.addEventListener('resize', () => {
            updateMaskPositionNew(newSteps[newCurrentStep].selector);
        });
    </script>

    <!-- HTML e JavaScript para o tutorial -->
    <?php if ($showTutorial): ?>
        <div class="overlay" id="tutorialOverlay">
            <button class="back-button" id="backButton" onclick="prevStep()">
                <span class="material-symbols-outlined">arrow_back_ios</span>
            </button>

            <div class="content-wrapper">
                <div class="content-header">
                    <h2 id="stepTitle"></h2>
                </div>
                <div class="content">
                    <img id="stepImage" src="" alt="Tutorial Step" />
                    <p id="stepText"></p>
                </div>
            </div>

            <button class="next-button" onclick="nextStep()">
                <span class="material-symbols-outlined">arrow_forward_ios</span>
            </button>
            <a href="" class="close-button" onclick="closeTutorial()">Dispensar</a>
        </div>
    <?php endif; ?>

    <script>
        const steps = [
            {
                selector: '#calendar',
                image: '../img/tutorial/calendario.gif',
                text: 'Nas setas acima do calendário você pode avançar e retroceder.\n' +
                    'Quando você está na tela dos meses, os meses serão alterados, e quando na tela dos anos, os anos serão alterados.',
                title: 'Avançar e Retroceder ',
                size: 25
            },
            {
                selector: '#calendar',
                image: '../img/tutorial/evento.gif',
                text: 'Ao clicar em uma data, você poderá criar um evento nesse dia.\n' +
                    'Basta preencher o título do evento, descrição e selecionar uma cor.\n' +
                    '\n' +
                    '(A descrição é opcional, você pode deixá - la em branco se preferir)',
                title: 'Criar Eventos',
                size: 30
            },
            {
                selector: '#calendar',
                image: '../img/tutorial/edicao.gif',
                text: 'Ao clicar em um evento criado, você poderá editar e excluir esse evento pelos ícones no canto inferior direito.\n' +
                    '\n' +
                    'O pincel é responsável pela edição, basta clicar nele, alterar as informações anteriormente preenchidas e concluir a alteração clicando no ícone roxo no canto inferior direito.\n' +
                    '\n' +
                    'O lixo é responsável pela exclusão, ao clicar nele seu evento será excluído.',
                title: 'Edição e Exclusão',
                size: 30
            },
        ];

        let currentStep = 0;

        // Atualiza a posição da máscara no overlay
        function updateMaskPosition(selector) {
            const element = document.querySelector(selector);
            if (element) {
                const rect = element.getBoundingClientRect();
                const x = rect.left + rect.width / 2;
                const y = rect.top + rect.height / 2;
                const size = steps[currentStep].size;

                const overlay = document.getElementById('tutorialOverlay');
                overlay.style.maskImage = `radial-gradient(circle ${size}px at ${x}px ${y}px, transparent 0%, black 0%)`;
                overlay.style.webkitMaskImage = `radial-gradient(circle ${size}px at ${x}px ${y}px, transparent 0%, black 0%)`;
            }
        }

        // Atualiza o conteúdo do tutorial (imagem, texto e título)
        function updateContent() {
            const step = steps[currentStep];
            document.getElementById('stepImage').src = step.image;
            document.getElementById('stepText').innerText = step.text;
            document.getElementById('stepTitle').innerText = step.title;

            // Controla a visibilidade das setas
            const backButton = document.getElementById('backButton');
            const nextButton = document.querySelector('.next-button');

            // Se estamos no primeiro passo, esconder o botão de voltar
            if (currentStep === 0) {
                backButton.style.display = 'none';
            } else {
                backButton.style.display = 'block';
            }

            // Se estamos no último passo, esconder o botão de avançar
            if (currentStep === steps.length - 1) {
                nextButton.style.display = 'none';
            } else {
                nextButton.style.display = 'block';
            }
        }

        // Avançar para o próximo passo
        function nextStep() {
            if (currentStep < steps.length - 1) {
                currentStep++;
                updateMaskPosition(steps[currentStep].selector);
                updateContent();
            } else {
                closeTutorial();
            }
        }

        // Voltar para o passo anterior
        function prevStep() {
            if (currentStep > 0) {
                currentStep--;
                updateMaskPosition(steps[currentStep].selector);
                updateContent();
            }
        }

        // Fechar o tutorial
        function closeTutorial() {
            document.getElementById('tutorialOverlay').style.display = 'none';
            dispensarTutorial();
        }

        // Marcar o tutorial como dispensado
        function dispensarTutorial() {
            fetch('', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: 'action=dispensar_tutorial'
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        console.log('Tutorial concluído e marcado como dispensado.');
                    } else {
                        console.error('Erro ao atualizar:', data.error);
                    }
                })
                .catch(error => console.error('Erro na requisição:', error));
        }

        // Iniciar o tutorial
        function startTutorial() {
            setTimeout(() => {
                updateMaskPosition(steps[currentStep].selector);
                updateContent();
            }, 100);
        }

        // Iniciar o tutorial caso a variável $showTutorial seja verdadeira
        <?php if ($showTutorial): ?>
            startTutorial();
        <?php endif; ?>

        // Atualizar a posição da máscara quando a janela for redimensionada
        window.addEventListener('resize', () => {
            updateMaskPosition(steps[currentStep].selector);
        });

        // Iniciar o tutorial
        function startTutorial() {
            setTimeout(() => {
                updateMaskPosition(steps[currentStep].selector);
                updateContent();
            }, 100);
        }

        // Iniciar o tutorial caso $showTutorial seja verdadeiro
        const showTutorialInitially = <?php echo json_encode($showTutorial); ?>;
        if (showTutorialInitially) {
            document.getElementById('tutorialOverlay').style.display = 'flex';
            startTutorial();
        }

        // Evento para abrir o tutorial ao clicar no botão de ajuda
        document.getElementById('helpButton').addEventListener('click', () => {
            document.getElementById('tutorialOverlay').style.display = 'flex';
            currentStep = 0; // Reinicia o tutorial
            startTutorial();
        });

        // Atualizar a posição da máscara quando a janela for redimensionada
        window.addEventListener('resize', () => {
            updateMaskPosition(steps[currentStep].selector);
        });
    </script>

    <div class="container-calendar">

        <span id="msg"></span>

        <div id='calendar'>

            <p class="textmuted apoio mt-3" style="font-size: 12px; text-align: center;"></p>

        </div>

    </div>

    <!-- Modal Visualizar -->
    <div class="modal fade" id="visualizarModal" tabindex="-1" aria-labelledby="visualizarModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div id="sidebarcolor" class="sidebar-color">
                    <input type="text" class="d-none" id="sidebarColorInput" value="">
                </div>

                <div class="modal-header" style="justify-content: flex-end; padding: 0px;">
                    <h1 class="modal-title fs-5 d-none" style="margin-left: 20px;" id="visualizarModalLabel">Visualizar
                        o
                        Evento</h1>
                    <h1 class="modal-title fs-5" style="margin-left: 20px;" id="editarModalLabel"
                        style="display: none;"></h1>
                    <button type="button" class="btn-cl" data-bs-dismiss="modal">close</button>
                </div>
                <div class="modal-body" style="padding-top: 5px;">

                    <span id="msgViewEvento"></span>

                    <div id="visualizarEvento">

                        <dl class="row">
                            <dt class="col-sm-3 d-none">ID: </dt>
                            <dd class="col-sm-9 d-none" id="visualizar_id"></dd>

                            <dt class="col-sm-3">Título: </dt>
                            <dd class="col-sm-9" id="visualizar_title"></dd>

                            <dt class="col-sm-3">Descrição: </dt>
                            <dd class="col-sm-9" id="visualizar_obs"></dd>

                            <dt class="col-sm-3 hidden">Início: </dt>
                            <dd class="col-sm-9 hidden" id="visualizar_start"></dd>

                            <dt class="col-sm-3 hidden">Fim: </dt>
                            <dd class="col-sm-9 hidden" id="visualizar_end"></dd>

                            <dt class="col-sm-3 hidden">Cor: </dt>
                            <dd class="col-sm-9 hidden" id="visualizar_color"></dd>
                        </dl>

                        <button style="background: transparent; margin-left: 80%; border: 0px; font-size: 30px;"
                            type="button" class="material-symbols-outlined brush" id="btnViewEditEvento">
                            brush</button>

                        <button style="background: transparent; border: 0px; font-size: 30px; color: #ff0000;"
                            type="button" class="material-symbols-outlined" id="btnApagarEvento">
                            delete</button>

                    </div>

                    <!-- Modal de confirmação -->
                    <div id="confirmModal" class="modal-confirm">
                        <div class="modal-content">
                            <p>Tem certeza de que deseja apagar este evento?</p>
                            <div class="modal-buttons" style="align-self: center;">
                                <button id="confirmYes" class="btn btn-confirm">Sim</button>
                                <button id="confirmNo" class="btn btn-cancel">Não</button>
                            </div>
                        </div>
                    </div>

                    <div id="editarEvento" style="display: none;">
                        <span id="msgEditEvento"></span>

                        <form method="POST" id="formEditEvento">
                            <input type="hidden" name="edit_id" id="edit_id">

                            <div class="row mb-3" style="width: 125%;">
                                <label for="edit_title" class="col-sm-2 col-form-label d-none">Título:</label>
                                <div class="col-sm-10">
                                    <input type="text" name="edit_title" class="form-control txtarea" id="edit_title"
                                        placeholder="Título do evento">
                                </div>
                            </div>

                            <div class="row mb-3" style="width: 125%;">
                                <label for="edit_obs" class="col-sm-2 col-form-label d-none">Descrição:</label>
                                <div class="col-sm-10">
                                    <input type="text" name="edit_obs" class="form-control txtarea" id="edit_obs"
                                        placeholder="Descrição do evento">
                                </div>
                            </div>

                            <div class="row mb-3 d-none">
                                <label for="edit_start" class="col-sm-2 col-form-label d-none">Início:</label>
                                <div class="col-sm-10">
                                    <input type="datetime-local" name="edit_start" class="form-control date hidden"
                                        id="edit_start">
                                </div>
                            </div>

                            <div class="row mb-3 d-none">
                                <label for="edit_end" class="col-sm-2 col-form-label d-none">Fim:</label>
                                <div class="col-sm-10">
                                    <input type="datetime-local" name="edit_end" class="form-control date hidden"
                                        id="edit_end">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="edit_color" class="col-sm-2 col-form-label d-none">Cor</label>
                                <div class="bolinhas col-sm-10 d-flex flex-wrap justify-content-between">

                                    <div class="form-check form-check-inline hidden">
                                        <input style="background-color: #F95B99;" class="form-check-input" type="radio"
                                            name="edit_color" id="edit_color" value="#F95B99">
                                    </div>

                                    <div class="form-check form-check-inline">
                                        <input style="background-color: #8C52FF;" class="form-check-input" type="radio"
                                            name="edit_color" id="edit_color_3" value="#8C52FF">
                                    </div>

                                    <div class="form-check form-check-inline">
                                        <input style="background-color: #CB6CE6;" class="form-check-input" type="radio"
                                            name="edit_color" id="edit_color_2" value="#CB6CE6">
                                    </div>

                                    <div class="form-check form-check-inline">
                                        <input style="background-color: #AA8DE4;" class="form-check-input" type="radio"
                                            name="edit_color" id="edit_color_4" value="#AA8DE4">
                                    </div>

                                    <div class="form-check form-check-inline">
                                        <input style="background-color: #F95B99;" class="form-check-input" type="radio"
                                            name="edit_color" id="edit_color" value="#F95B99">
                                    </div>

                                    <div class="form-check form-check-inline">
                                        <input style="background-color: #FF5757;" class="form-check-input" type="radio"
                                            name="edit_color" id="edit_color_5" value="#FF5757">
                                    </div>

                                    <div class="form-check form-check-inline">
                                        <input style="background-color: #FF914D;" class="form-check-input" type="radio"
                                            name="edit_color" id="edit_color_6" value="#FF914D">
                                    </div>

                                    <div class="form-check form-check-inline">
                                        <input style="background-color: #FFCF52;" class="form-check-input" type="radio"
                                            name="edit_color" id="edit_color_7" value="#FFCF52">
                                    </div>

                                    <div class="form-check form-check-inline">
                                        <input style="background-color: #FFF178;" class="form-check-input" type="radio"
                                            name="edit_color" id="edit_color_8" value="#FFF178">
                                    </div>

                                    <div class="form-check form-check-inline">
                                        <input style="background-color: #00FFFF;" class="form-check-input" type="radio"
                                            name="edit_color" id="edit_color_9" value="#00FFFF">
                                    </div>

                                    <div class="form-check form-check-inline">
                                        <input style="background-color: #3091FF;" class="form-check-input" type="radio"
                                            name="edit_color" id="edit_color_10" value="#3091FF">
                                    </div>

                                    <div class="form-check form-check-inline">
                                        <input style="background-color: #397D1D;" class="form-check-input" type="radio"
                                            name="edit_color" id="edit_color_11" value="#397D1D">
                                    </div>

                                    <div class="form-check form-check-inline">
                                        <input style="background-color: #36DF32;" class="form-check-input" type="radio"
                                            name="edit_color" id="edit_color_12" value="#36DF32">
                                    </div>
                                </div>
                            </div>

                            <button style="background: transparent; border: 0px; font-size: 30px;" type="button"
                                class="material-symbols-outlined back" id="btnViewEvento">
                                arrow_back</button>

                            <button style="margin-left: 80%; background: transparent; border: 0px; font-size: 30px;"
                                type="submit" name="btnEditEvento" id="btnEditEvento"
                                class="material-symbols-outlined purple">send</button>

                        </form>

                    </div>

                </div>
            </div>
        </div>
    </div>

    <script>




    </script>

    <!-- Modal Cadastrar -->
    <div class="modal fade modal-calendario" id="cadastrarModal" tabindex="-1" aria-labelledby="cadastrarModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div id="sidebarcolor_cad" class="sidebar-color"></div>

                <div class="modal-header" style="justify-content: flex-end; padding: 0px;">
                    <h1 class="modal-title fs-5" style="margin-left: 20px;" id="cadastrarModalLabel">
                    </h1>
                    <button type="button" class="btn-cl" data-bs-dismiss="modal">close</button>
                </div>
                <div class="modal-body d-flex flex-column align-items-center" style="padding-top: 5px;">

                    <span id="msgCadEvento"></span>

                    <form method="POST" id="formCadEvento" class="w-100">
                        <div class="row mb-3" style="width: 125%;">
                            <label for="cad_title" class="col-sm-2 col-form-label d-none">Título:</label>
                            <div class="col-sm-10">
                                <input type="text" name="cad_title" class="form-control txtarea" id="cad_title"
                                    placeholder="Título do evento" required>
                            </div>
                        </div>

                        <div class="row mb-3" style="width: 125%;">
                            <label for="cad_obs" class="col-sm-2 col-form-label d-none">Descrição:</label>
                            <div class="col-sm-10">
                                <input type="text" name="cad_obs" class="form-control txtarea" id="cad_obs"
                                    placeholder="Descrição do evento">
                            </div>
                        </div>

                        <div class="row mb-3 d-none">
                            <label for="cad_start" class="col-sm-2 col-form-label d-none">Início:</label>
                            <div class="col-sm-10">
                                <input type="datetime-local" name="cad_start" class="form-control date hidden"
                                    id="cad_start">
                            </div>
                        </div>

                        <div class="row mb-3 d-none">
                            <label for="cad_end" class="col-sm-2 col-form-label d-none">Fim:</label>
                            <div class="col-sm-10">
                                <input type="datetime-local" name="cad_end" class="form-control date hidden"
                                    id="cad_end">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="cad_color" class="col-sm-2 col-form-label d-none">Cor:</label>
                            <div class="bolinhas col-sm-10 d-flex flex-wrap justify-content-between">

                                <!-- Adicione as cores conforme o padrão -->
                                <div class="form-check form-check-inline">
                                    <input style="background-color: #8C52FF;" class="form-check-input" type="radio"
                                        name="cad_color" id="color1" value="#8C52FF" checked>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input style="background-color: #CB6CE6;" class="form-check-input" type="radio"
                                        name="cad_color" id="color2" value="#CB6CE6">
                                </div>
                                <div class="form-check form-check-inline">
                                    <input style="background-color: #AA8DE4;" class="form-check-input" type="radio"
                                        name="cad_color" id="color3" value="#AA8DE4">
                                </div>
                                <div class="form-check form-check-inline">
                                    <input style="background-color: #F95B99;" class="form-check-input" type="radio"
                                        name="cad_color" id="color4" value="#F95B99">
                                </div>
                                <div class="form-check form-check-inline">
                                    <input style="background-color: #FF5757;" class="form-check-input" type="radio"
                                        name="cad_color" id="color5" value="#FF5757">
                                </div>
                                <div class="form-check form-check-inline">
                                    <input style="background-color: #FF914D;" class="form-check-input" type="radio"
                                        name="cad_color" id="color6" value="#FF914D">
                                </div>
                                <div class="form-check form-check-inline">
                                    <input style="background-color: #FFCF52;" class="form-check-input" type="radio"
                                        name="cad_color" id="color7" value="#FFCF52">
                                </div>
                                <div class="form-check form-check-inline">
                                    <input style="background-color: #FFF178;" class="form-check-input" type="radio"
                                        name="cad_color" id="color8" value="#FFF178">
                                </div>
                                <div class="form-check form-check-inline">
                                    <input style="background-color: #00FFFF;" class="form-check-input" type="radio"
                                        name="cad_color" id="color9" value="#00FFFF">
                                </div>
                                <div class="form-check form-check-inline">
                                    <input style="background-color: #3091FF;" class="form-check-input" type="radio"
                                        name="cad_color" id="color10" value="#3091FF">
                                </div>
                                <div class="form-check form-check-inline">
                                    <input style="background-color: #397D1D;" class="form-check-input" type="radio"
                                        name="cad_color" id="color11" value="#397D1D">
                                </div>
                                <div class="form-check form-check-inline">
                                    <input style="background-color: #36DF32;" class="form-check-input" type="radio"
                                        name="cad_color" id="color12" value="#36DF32">
                                </div>
                            </div>
                        </div>

                        <button style="margin-left: 90%; background: transparent; border: 0px; font-size: 30px;"
                            type="submit" name="btnCadEvento" id="btnCadEvento"
                            class="material-symbols-outlined purple">
                            send</button>

                    </form>

                </div>
            </div>
        </div>
    </div>

    <div id="popup-overlay"
        style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); z-index: 999;">
    </div>

    <div id="popup"
        style="display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); background: #fff; padding: 20px; border-radius: 5px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); z-index: 1000;">
        <p id="popup-message" style="margin: 0; font-size: 18px; font-weight: bold;"></p>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4"
        crossorigin="anonymous"></script>
    <script src='js/index.global.min.js'></script>
    <script src="js/bootstrap5/index.global.min.js"></script>
    <script src='js/core/locales-all.global.min.js'></script>
    <script src='js/custom.js'></script>

    <script>
        // Função para exibir o popup com a mensagem
        function showPopupMessage(message) {
            document.getElementById('popup-message').innerText = message;
            document.getElementById('popup-overlay').style.display = 'block';
            document.getElementById('popup').style.display = 'block';

            setTimeout(function () {
                document.getElementById('popup-overlay').style.display = 'none';
                document.getElementById('popup').style.display = 'none';
            }, 3000);
        }

        // Função chamada no envio do formulário
        document.getElementById('formCadEvento').addEventListener('submit', function (e) {
            e.preventDefault(); // Impede o envio padrão do formulário

            // Lógica de envio do formulário (AJAX ou outro método)

            // Armazena o sucesso no sessionStorage
            sessionStorage.setItem('eventoCadastrado', 'true');

            // Recarrega a página
            location.reload();
        });

        // Verifica se o evento foi cadastrado após o reload da página
        document.addEventListener('DOMContentLoaded', function () {
            // Se houver a chave 'eventoCadastrado' no sessionStorage, exibe o popup
            if (sessionStorage.getItem('eventoCadastrado') === 'true') {
                showPopupMessage("Evento cadastrado com sucesso");
                sessionStorage.removeItem('eventoCadastrado'); // Remove a chave após exibir o popup
            }
        });

        // Função para atualizar a cor da barra lateral no modal de edição
        function updateSidebarEditColor(color) {
            const sidebarColorElement = document.getElementById('sidebarcolor');
            if (sidebarColorElement) {
                sidebarColorElement.style.backgroundColor = color;
            }
        }

        // Seleciona todos os radio buttons que representam as cores no modal de edição
        const colorRadios = document.querySelectorAll('input[name="edit_color"]');
        colorRadios.forEach(radio => {
            radio.addEventListener('change', function () {
                if (this.checked) {
                    updateSidebarEditColor(this.value); // Atualiza a cor da barra lateral no modal de edição
                }
            });
        });

        // Função para atualizar a cor da barra lateral no modal de cadastro
        function updateSidebarCadColor(cad_color) {
            const sidebarColorElement = document.getElementById('sidebarcolor_cad');
            if (sidebarColorElement) {
                sidebarColorElement.style.backgroundColor = cad_color;
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            // Atualização da barra lateral no modal de cadastro
            const colorInputsCad = document.querySelectorAll('input[name="cad_color"]');
            colorInputsCad.forEach(input => {
                input.addEventListener('change', function () {
                    updateSidebarCadColor(this.value); // Chama a função para atualizar a cor no modal de cadastro
                });
            });
        });

        // Exibe a cor do evento quando o modal de visualização é aberto
        document.getElementById('visualizarModal').addEventListener('show.bs.modal', function (event) {
            const eventColor = event.relatedTarget.getAttribute('data-event-color');
            updateSidebarEditColor(eventColor); // Atualiza a cor da barra lateral no modal de edição
        });

        document.getElementById('formCadEvento').addEventListener('submit', function (e) {
            var titulo = document.getElementById('cad_title').value;
            var descricao = document.getElementById('cad_obs').value;

            // Validação do campo título
            if (!titulo) {
                alert('Por favor, preencha o título do evento.');
                e.preventDefault(); // Impede o envio do formulário
                return false;
            }

            // Verifica se a descrição está vazia, se sim, define "Evento sem descrição"
            if (!descricao) {
                document.getElementById('cad_obs').value = "Evento sem descrição.";
            }

            // Aqui você pode adicionar mais validações, se necessário
        });
    </script>

    <script>
        // Função para atualizar o valor do input com a cor de fundo da div
        function updateInputWithBackgroundColor() {
            const sidebarColorDiv = document.getElementById('sidebarcolor');
            const sidebarColorInput = document.getElementById('sidebarColorInput');

            // Obtém o estilo de fundo da div e atribui ao valor do input
            const backgroundColor = window.getComputedStyle(sidebarColorDiv).backgroundColor;
            sidebarColorInput.value = backgroundColor;
        }

        // Inicializa o valor do input ao carregar a página
        updateInputWithBackgroundColor();

        // Exemplo: Atualize o valor do input quando a cor de fundo mudar (caso seja mudada por um evento)
        sidebarColorDiv.style.backgroundColor = "nova_cor"; // Exemplo de como alterar a cor
        updateInputWithBackgroundColor(); // Chame a função novamente para atualizar o input
    </script>

</body>

</html>