<!--SIDEBAR-->
<?php

if (!isset($_SESSION['idUsuario'])) {
    header('Location: login.php');
    exit();
}

$conn = mysqli_connect('127.0.0.1:3306', 'u116672606_daybyday', 'D@ybyday02', 'u116672606_tcc');
if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Erro de conexão: " . $conn->connect_error]));
}

$id_usuario = $_SESSION['idUsuario'];
$sql = "SELECT id, nome, cor FROM categoria WHERE id_usuario = '$id_usuario'";
$result = $conn->query($sql);
?>

<style>
    /* Zoom ao passar o mouse */
    #side_items .side-item a:hover {
        transform: scale(1.1);
        /* Aumenta o tamanho ao passar o mouse ou estar ativo */
        color: var(--purple);
    }

    #side_items .side-item.active a {
        transform: scale(1.1);
        /* Aumenta o tamanho ao passar o mouse ou estar ativo */
        color: var(--purple);
        /* Altera a cor do texto no hover e estado ativo */
        background-color: var(--sidebar-active);
        /* Define a cor de fundo quando ativo */
    }

    /* Para ícones na navbar */
    #side_items .side-item ion-icon {
        transition: transform 0.3s ease;
    }

    /* Zoom nos ícones ao passar o mouse e quando ativo */
    #side_items .side-item:hover ion-icon,
    #side_items .side-item.active ion-icon {
        transform: scale(1.2);
        /* Aplica o efeito de zoom nos ícones também */
    }

    /* Estilo específico para o link ativo */
    #side_items .side-item.active a {
        transform: scale(1.1);
        /* Garante que o link ativo tenha o mesmo tamanho que o hover */
        background-color: var(--sidebar-active);
        /* Define a cor de fundo quando ativo */
        color: var(--purple);
        /* Mantém a cor do texto */
    }

    /* Estilos para o conteúdo do acordeão (inicialmente oculto) */
    .accordion-content {
        max-height: 0;
        opacity: 0;
        /* Mantém a opacidade 0 inicialmente */
        overflow: hidden;
        transform: translateY(-20px);
        /* Move o conteúdo para cima inicialmente */
        transition: max-height 0.5s ease, transform 0.5s ease, opacity 0.5s ease;
        /* Animação suave */
        visibility: hidden;
        /* Mantém o conteúdo invisível quando fechado */
    }

    /* Ativa a animação de deslizamento para baixo */
    .accordion-content.active {
        max-height: 200px;
        /* Defina um valor que possa acomodar o conteúdo expandido */
        opacity: 1;
        /* Mantém a opacidade 1 quando visível */
        transform: translateY(0);
        /* Move para a posição original */
        visibility: visible;
        /* Torna o conteúdo visível */
    }
</style>

<body>

    <div id="main-content">
        <!-- O conteúdo principal será carregado aqui -->
    </div>

    <div class="sidebar d-flex">
        <nav id="sidebar">
            <div id="sidebar_content">

                <!--LINKS-->
                <ul id="side_items">
                    
                    <li class="side-item">
                        <a href="?p=calendario">
                            <ion-icon name="calendar-outline" class="purple" style="z-index: 99; font-size: 25px;">
                            </ion-icon>
                            <span class="item-description">
                                Calendário
                            </span>
                        </a>
                    </li>

                    <li class="side-item">
                        <a href="?p=notas">
                            <ion-icon name="document-text-outline" class="purple"
                                style=" z-index: 99; font-size: 25px;">
                            </ion-icon>
                            <span class="item-description">
                                Notas
                            </span>
                        </a>
                    </li>

                    <hr class="hr-muted">
                    <li class="side-item">
                        <a class="d-flex align-items-center" id="categoria-layout" role="button">
                            <div class="head d-flex align-items-center">
                                <ion-icon name="newspaper-outline" id="icon-categoria"></ion-icon>
                                <span class="item-description">Categorias</span>
                                <ion-icon name="chevron-down-outline" class="accordion-toggle"
                                    id="toggle-icon"></ion-icon>
                            </div>
                        </a>
                        <div class="accordion-content" id="accordion-categorias">
                            <div class="accordion-body">
                                <?php if ($result->num_rows > 0): ?>
                                    <ul class="category-list mt-2">
                                        <?php while ($row = $result->fetch_assoc()): ?>
                                            <?php
                                            $textColor = (in_array($row['cor'], ['#FFFF00', '#36DF32', '#FFCF52', '#00FFFF'])) ? 'black' : 'white';
                                            ?>
                                            <li class='category-item'
                                                style='background-color: <?php echo $row['cor']; ?>; color: <?php echo $textColor; ?>; display: flex; justify-content: space-between; align-items: center;'>
                                                <span class="category-name"
                                                    style="max-width: 70%; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                                    <?php echo $row['nome']; ?>
                                                </span>
                                                <div class="button-container" style="display: flex; gap: 10px;">
                                                    <button class="ion-icon-btn" style="color: <?php echo $textColor; ?>;"
                                                        onclick="openModal('edit', <?php echo $row['id']; ?>, '<?php echo $row['nome']; ?>', '<?php echo $row['cor']; ?>')">
                                                        <ion-icon name='create-outline'></ion-icon>
                                                    </button>
                                                    <button class="ion-icon-btn" style="color: <?php echo $textColor; ?>;"
                                                        onclick="openModal('delete', <?php echo $row['id']; ?>)">
                                                        <ion-icon name='trash-outline'></ion-icon>
                                                    </button>
                                                </div>
                                            </li>
                                        <?php endwhile; ?>
                                    </ul>
                                <?php endif; ?>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>

    </div>

    <script>

        document.addEventListener("DOMContentLoaded", function () {
            const currentUrl = window.location.search;
            const links = document.querySelectorAll("#side_items a");

            links.forEach(link => {
                if (link.getAttribute("href") === currentUrl) {
                    link.classList.add("active");
                    // Adiciona a classe active ao li pai
                    link.closest('.side-item').classList.add('active');
                }
            });
        });


        const categoriaLayout = document.getElementById('categoria-layout');
        const content = document.getElementById('accordion-categorias');
        const toggleIcon = document.getElementById('toggle-icon');

        categoriaLayout.addEventListener('click', function () {
            // Alterna a classe 'active' no conteúdo
            const isActive = content.classList.toggle('active');

            // Verifica se a classe 'active' está presente
            if (isActive) {
                toggleIcon.classList.add('rotate'); // Adiciona a rotação se ativo
            } else {
                toggleIcon.classList.remove('rotate'); // Remove a rotação se inativo
            }
        });

        document.addEventListener("DOMContentLoaded", function () {
            // Obtém a URL atual
            const currentUrl = window.location.search;

            // Seleciona todos os links dentro de #side_items
            const links = document.querySelectorAll("#side_items a");

            // Itera sobre cada link
            links.forEach(link => {
                // Verifica se o href do link corresponde à URL atual
                if (link.getAttribute("href") === currentUrl) {
                    // Adiciona a classe 'active' ao link correspondente
                    link.classList.add("active");
                }
            });
        });

    </script>

    <!-- Modal Categoria -->
    <div id="modal-overlayy" class="modal-overlayy"></div>
    <div id="modal-cat" class="modal-cat">

        <form id="modal-form" method="POST" action="editar_categoria.php">
            <input type="hidden" name="action" id="modal-action">
            <input type="hidden" name="id_categoria" id="modal-id_categoria">
            <div id="modal-content"></div>
        </form>
    </div>

    <!-- SCRIPTS -->
    <!-- SCRIPTS -->
    <!-- SCRIPTS -->
    <script>
        function openModal(action, id_categoria, nome_categoria = '', cor_escolhida = '') {
            var modal = document.getElementById('modal-cat');
            var overlay = document.getElementById('modal-overlayy');
            var form = document.getElementById('modal-form');
            var content = document.getElementById('modal-content');

            // Configura o formulário
            document.getElementById('modal-action').value = action;
            document.getElementById('modal-id_categoria').value = id_categoria;

            if (action === 'edit') {
                content.innerHTML = `
        <div class="head-modal">
            <h5 class="modal-title" id="myModalLabel">Edite sua Categoria</h5>
            <button type="button" class="btn-cl" onclick="closeModal()">close</button>
        </div>
        <div class="modal-body p-0">
            <form id="categoryForm" action="editar_categoria.php" onsubmit="return validateForm()" method="post">
                <div class="form-group">
                    <label for="categoryName" class="hidden">Nome</label>
                    <input type="text" class="form-control" id="categoryName" name="nome_categoria" value="${nome_categoria}" required>
                </div>
                <div class="form-group mt-4" id="cubo-colors">
                    <label>Cor:</label><br>
                    <div class="row mt-3">
                        <div class="col-3 text-center">
                            <input type="radio" class="color-checkbox" id="colorF95B99" value="#F95B99" name="cor_escolhida" ${cor_escolhida === '#F95B99' ? 'checked' : ''}>
                            <label for="colorF95B99">
                                <div class="color-option" style="background-color: #F95B99;"></div>
                            </label>
                        </div>
                        <div class="col-3 text-center">
                            <input type="radio" class="color-checkbox" id="colorCB6CE6" value="#CB6CE6" name="cor_escolhida" ${cor_escolhida === '#CB6CE6' ? 'checked' : ''}>
                            <label for="colorCB6CE6">
                                <div class="color-option" style="background-color: #CB6CE6;"></div>
                            </label>
                        </div>
                        <div class="col-3 text-center">
                            <input type="radio" class="color-checkbox" id="color8C52FF" value="#8C52FF" name="cor_escolhida" ${cor_escolhida === '#8C52FF' ? 'checked' : ''}>
                            <label for="color8C52FF">
                                <div class="color-option" style="background-color: #8C52FF;"></div>
                            </label>
                        </div>
                        <div class="col-3 text-center">
                            <input type="radio" class="color-checkbox" id="colorAA8DE4" value="#AA8DE4" name="cor_escolhida" ${cor_escolhida === '#AA8DE4' ? 'checked' : ''}>
                            <label for="colorAA8DE4">
                                <div class="color-option" style="background-color: #AA8DE4;"></div>
                            </label>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-3 text-center">
                            <input type="radio" class="color-checkbox" id="colorFF5757" value="#FF5757" name="cor_escolhida" ${cor_escolhida === '#FF5757' ? 'checked' : ''}>
                            <label for="colorFF5757">
                                <div class="color-option" style="background-color: #FF5757;"></div>
                            </label>
                        </div>
                        <div class="col-3 text-center">
                            <input type="radio" class="color-checkbox" id="colorFFFF00" value="#FFFF00" name="cor_escolhida" ${cor_escolhida === '#FFFF00' ? 'checked' : ''}>
                            <label for="colorFFFF00">
                                <div class="color-option" style="background-color: #FFFF00;"></div>
                            </label>
                        </div>
                        <div class="col-3 text-center">
                            <input type="radio" class="color-checkbox" id="colorFFCF52" value="#FFCF52" name="cor_escolhida" ${cor_escolhida === '#FFCF52' ? 'checked' : ''}>
                            <label for="colorFFCF52">
                                <div class="color-option" style="background-color: #FFCF52;"></div>
                            </label>
                        </div>
                        <div class="col-3 text-center">
                            <input type="radio" class="color-checkbox" id="colorFF914D" value="#FF914D" name="cor_escolhida" ${cor_escolhida === '#FF914D' ? 'checked' : ''}>
                            <label for="colorFF914D">
                                <div class="color-option" style="background-color: #FF914D;"></div>
                            </label>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-3 text-center">
                            <input type="radio" class="color-checkbox" id="color36DF32" value="#36DF32" name="cor_escolhida" ${cor_escolhida === '#36DF32' ? 'checked' : ''}>
                            <label for="color36DF32">
                                <div class="color-option" style="background-color: #36DF32;"></div>
                            </label>
                        </div>
                        <div class="col-3 text-center">
                            <input type="radio" class="color-checkbox" id="color397D1D" value="#397D1D" name="cor_escolhida" ${cor_escolhida === '#397D1D' ? 'checked' : ''}>
                            <label for="color397D1D">
                                <div class="color-option" style="background-color: #397D1D;"></div>
                            </label>
                        </div>
                        <div class="col-3 text-center">
                            <input type="radio" class="color-checkbox" id="color00FFFF" value="#00FFFF" name="cor_escolhida" ${cor_escolhida === '#00FFFF' ? 'checked' : ''}>
                            <label for="color00FFFF">
                                <div class="color-option" style="background-color: #00FFFF;"></div>
                            </label>
                        </div>
                        <div class="col-3 text-center">
                            <input type="radio" class="color-checkbox" id="color3091FF" value="#3091FF" name="cor_escolhida" ${cor_escolhida === '#3091FF' ? 'checked' : ''}>
                            <label for="color3091FF">
                                <div class="color-option" style="background-color: #3091FF;"></div>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="push-cat justify-content-end mb-2 text-center align-items-center d-flex" style="gap: 10px">
                    <button class="btn-cat-action material-symbols-outlined" style="color: var(--purple); background: none; border: none; font-size: 30px;" id="btn-confirm" type="submit">Send</button>
                </div>
            </form>
        </div>
    `;
            } else if (action === 'delete') {
                content.innerHTML = `<p>Você tem certeza que deseja excluir esta categoria?</p>
                <div style="    align-items: center;
    display: flex;
    justify-content: center; margin-top: -20px;
    gap: 10px;">
                <button class="btn-cat-action" id="btn-confirm" type="submit">Confirmar</button>
                <button class="btn-cat-action" id="btn-cancel" type="button" onclick="closeModal()">Cancelar</button>
                </div>`;
            }

            modal.style.display = 'block';
            overlay.style.display = 'block';
        }

        function closeModal() {
            var modal = document.getElementById('modal-cat');
            var overlay = document.getElementById('modal-overlayy');
            modal.style.display = 'none';
            overlay.style.display = 'none';
        }

        document.getElementById('modal-overlayy').addEventListener('click', closeModal);

    </script>


    <script>
        const chk = document.getElementById('chk');
        const html = document.documentElement;
        const currentTheme = localStorage.getItem('theme');

        if (currentTheme) {
            html.classList.add(currentTheme);
            chk.checked = true;
        }

        chk.addEventListener('change', () => {
            html.classList.toggle('dark-theme');

            const theme = html.classList.contains('dark-theme') ? 'dark-theme' : '';
            localStorage.setItem('theme', theme);
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var settingsItem = document.getElementById('settingsItem');
            var dropdownMenu = document.getElementById('myDropdown');

            settingsItem.addEventListener('click', function () {
                dropdownMenu.classList.toggle('show');
            });

            // fecha o dropdown quando clicar fora dele
            window.addEventListener('click', function (event) {
                if (!dropdownMenu.contains(event.target) && !settingsItem.contains(event.target)) {
                    dropdownMenu.classList.remove('show');
                }
            });
        });
    </script>



    <script>
        document.getElementById('open_btn').addEventListener('click', function () {
            document.getElementById('sidebar').classList.toggle('open-sidebar');
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const openButton = document.getElementById('open_btn');
            const sidebar = document.getElementById('sidebar');

            openButton.addEventListener('click', function () {
                sidebar.classList.toggle('open-sidebar');
            });

            document.addEventListener('click', function (event) {
                if (!sidebar.contains(event.target) && !openButton.contains(event.target)) {
                    sidebar.classList.remove('open-sidebar'); // Fecha a sidebar
                }
            });
        });

        document.addEventListener('DOMContentLoaded', function () {
            const openButton = document.getElementById('open_btn'); // Botão para abrir a sidebar
            const sidebar = document.getElementById('sidebar'); // Sidebar
            const categoriasIcon = document.getElementById('icon-categoria'); // Ícone de categorias
            const dropdownMenu = document.getElementById('acordion-categorias'); // Menu dropdown

            // Função para alternar a sidebar
            function toggleSidebar() {
                sidebar.classList.toggle('open-sidebar');
            }

            // Função para garantir que o dropdown esteja visível
            function openSidebarWithDropdown() {
                toggleSidebar(); // Abre ou fecha a sidebar
                dropdownMenu.classList.add('show'); // Garante que o dropdown esteja visível
            }

            // Evento para o botão de abrir
            openButton.addEventListener('click', toggleSidebar);

            // Evento para o ícone de categorias
            categoriasIcon.addEventListener('click', function (event) {
                event.preventDefault(); // Impede o comportamento padrão do link
                openSidebarWithDropdown(); // Abre a sidebar e mostra o dropdown
            });

            // Evento para fechar a sidebar ao clicar fora
            document.addEventListener('click', function (event) {
                if (!sidebar.contains(event.target) && !openButton.contains(event.target) && !categoriasIcon.contains(event.target)) {
                    sidebar.classList.remove('open-sidebar'); // Fecha a sidebar
                    dropdownMenu.classList.remove('show'); // Fecha o dropdown
                }
            });
        });

    </script>

    <script>
        function validateForm() {
            // Seleciona todas as cores disponíveis
            var radios = document.getElementsByName('cor_escolhida');
            var formValid = false;

            // Verifica se algum dos radios está marcado
            for (var i = 0; i < radios.length; i++) {
                if (radios[i].checked) {
                    formValid = true;
                    break;
                }
            }

            // Se nenhuma cor foi selecionada, exibe um alerta e impede o envio do formulário
            if (!formValid) {
                alert("Por favor, escolha uma cor antes de enviar.");
                return false; // Impede o envio do formulário
            }

            return true; // Permite o envio do formulário se uma cor foi escolhida
        }
    </script>



</body>