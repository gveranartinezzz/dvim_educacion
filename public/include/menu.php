<?php
// Evitar reinicio de sesión si ya existe
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Función robusta para validar el rol (asegura que funcione sin importar mayúsculas)
function esAdmin() {
    return isset($_SESSION['rol']) && strtolower($_SESSION['rol']) === 'administrador';
}

$pagina_actual = basename($_SERVER['PHP_SELF']);
?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">

<nav class="navbar navbar-expand-lg navbar-dark shadow" 
     style="background: linear-gradient(90deg, #54063e 0%, #7d0a5d 100%); padding: 12px 0; position: sticky; top: 0; z-index: 1020;">
  <div class="container-fluid">
    <a class="navbar-brand" href="inicio.php">
      <span class="fw-bold text-white" style="letter-spacing: 1px; text-shadow: 1px 1px 2px rgba(0,0,0,0.3);">
        DIVM <span style="font-weight: 300; opacity: 0.9;">| Educación Especial</span>
      </span>
    </a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menu" style="border: none;">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="menu">
      <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
        <?php
        $botones = [
            'Inicio' => 'inicio.php',
            'Pacientes' => 'registro.php',
            'Planes' => 'plan.php',
            'Distribución' => 'distribucion.php',
            'Informes' => 'informe.php',
            'Avances' => 'avances.php',
            'Valoración' => 'valoracion.php',
            'Recomendaciones' => 'recomendaciones.php'
        ];

        // Lógica de botones: insertar Usuarios solo si es admin
        if (esAdmin()) {
            $botones = ['Usuarios' => 'usuarios.php'] + $botones;
        }

        foreach ($botones as $nombre => $link) {
            $clase_activa = ($pagina_actual == $link) ? 'active-link' : '';
            echo "
            <li class='nav-item'>
              <a class='nav-link text-white px-3 menu-link $clase_activa' href='$link' 
                 style='transition: all 0.3s; font-weight: 500;'>$nombre</a>
            </li>";
        }
        ?>
      </ul>

      <div class="d-flex align-items-center ms-lg-4 mt-3 mt-lg-0">
        <span class="badge me-3 text-uppercase" style="background-color: rgba(255,255,255,0.2); font-weight: 300;">
            <?php echo $_SESSION['rol'] ?? 'Invitado'; ?>
        </span>
        <a class="btn btn-logout" href="../servidor/logout.php">Cerrar Sesión</a>
      </div>
    </div>
  </div>
</nav>

<div class="wrapper">
    <?php 
    $titulo_panel = "Panel de Gestión";
    $elementos_sidebar = []; 
    $key_modulo = ""; 

    switch ($pagina_actual) {
        case 'usuarios.php':
            $titulo_panel = "Módulo Usuarios";
            $key_modulo = "lastTab_usuarios";
            $elementos_sidebar = [
                ['id' => 'alta-tab',   'target' => '#panel-alta',   'icono' => 'bi-person-plus', 'texto' => 'Alta de Usuario'],
                ['id' => 'lista-tab',  'target' => '#panel-lista',  'icono' => 'bi-list-ul',     'texto' => 'Lista de Usuarios'],
                ['id' => 'roles-tab',  'target' => '#panel-roles',  'icono' => 'bi-shield-lock', 'texto' => 'Roles y Permisos']
            ];
            break;
        case 'registro.php':
            $titulo_panel = "Módulo Pacientes";
            $key_modulo = "lastTab_registro";
            $elementos_sidebar = [
                ['id' => 'ingresar-tab',  'target' => '#panel-ingresar',  'icono' => 'bi-file-earmark-person', 'texto' => 'Registrar Paciente'],
                ['id' => 'consultar-tab', 'target' => '#panel-consultar', 'icono' => 'bi-list-ul',             'texto' => 'Consultar Historial'],
                ['id' => 'filtros-tab',   'target' => '#panel-filtros',   'icono' => 'bi-funnel-fill',         'texto' => 'Búsqueda y Filtros'],
                ['id' => 'estatus-tab',   'target' => '#panel-estatus',   'icono' => 'bi-toggle-on',           'texto' => 'Altas / Bajas (Estatus)']
            ];
            break;
        case 'plan.php':
            $titulo_panel = "Planes Pedagógicos";
            $key_modulo = "lastTab_plan";
            $elementos_sidebar = [
                ['id' => 'crear-plan-tab',  'target' => '#panel-crear-plan',  'icono' => 'bi-file-earmark-medical', 'texto' => 'Nuevo Plan'],
                ['id' => 'ver-planes-tab',  'target' => '#panel-ver-planes',  'icono' => 'bi-folder-shared',        'texto' => 'Ver Historial de Planes'],
                ['id' => 'buscar-plan-tab', 'target' => '#panel-buscar-plan', 'icono' => 'bi-funnel-fill',        'texto' => 'Búsqueda y Filtros'],
                ['id' => 'objetivos-tab',   'target' => '#panel-objetivos',   'icono' => 'bi-journal-bookmark',    'texto' => 'Plantillas y Objetivos']
            ];
            break;
        case 'distribucion.php':
            $titulo_panel = "Gestión de Horarios";
            $key_modulo = "lastTab_distribucion";
            $elementos_sidebar = [
                ['id' => 'enviar-tab',       'target' => '#panel-enviar',       'icono' => 'bi-calendar-plus',      'texto' => 'Asignar Horario'],
                ['id' => 'banco-tab',        'target' => '#panel-banco',        'icono' => 'bi-calendar-week',      'texto' => 'Horarios Registrados'],
                ['id' => 'distribucion-tab', 'target' => '#panel-distribucion', 'icono' => 'bi-funnel-fill',        'texto' => 'Distribución'],
                ['id' => 'guias-tab',        'target' => '#panel-guias',        'icono' => 'bi-info-circle-fill',   'texto' => 'Lineamientos']
            ];
            break;
        case 'informe.php':
            $titulo_panel = "Reportes e Informes";
            $key_modulo = "lastTab_informe";
            $elementos_sidebar = [
                ['id' => 'generar-tab',    'target' => '#panel-generar',    'icono' => 'bi-file-earmark-pdf',     'texto' => 'Generar Informe'],
                ['id' => 'archivo-tab',    'target' => '#panel-archivo',    'icono' => 'bi-archive',              'texto' => 'Archivo Clínico'],
                ['id' => 'filtros-tab',    'target' => '#panel-filtros',    'icono' => 'bi-funnel-fill',          'texto' => 'Búsqueda y Filtros'],
                ['id' => 'graficas-tab',   'target' => '#panel-graficas',   'icono' => 'bi-bar-chart-line',       'texto' => 'Estadísticas y Gráficas'],
                ['id' => 'plantillas-tab', 'target' => '#panel-plantillas', 'icono' => 'bi-journal-bookmark-fill', 'texto' => 'Plantillas y Criterios']
            ];
            break;
        case 'avances.php':
            $titulo_panel = "Avances de Pacientes";
            $key_modulo = "lastTab_avances";
            $elementos_sidebar = [
                ['id' => 'enviar-tab',   'target' => '#panel-enviar',   'icono' => 'bi-clipboard-data', 'texto' => 'Registrar Avance'],
                ['id' => 'banco-tab',    'target' => '#panel-banco',    'icono' => 'bi-table',          'texto' => 'Historial'],
                ['id' => 'detalle-tab',  'target' => '#panel-detalle',  'icono' => 'bi-journal-text',   'texto' => 'Detalle de Avances']
            ];
            break;
        case 'valoracion.php':
            $titulo_panel = "Valoración Inicial";
            $key_modulo = "lastTab_valoracion";
            $elementos_sidebar = [
                ['id' => 'generar-tab',   'target' => '#panel-enviar',  'icono' => 'bi-file-earmark-medical-fill', 'texto' => 'Nueva Valoración'],
                ['id' => 'archivo-tab',   'target' => '#panel-banco',    'icono' => 'bi-archive-fill',              'texto' => 'Historial Clínico'],
                ['id' => 'criterios-tab', 'target' => '#panel-filtros', 'icono' => 'bi-bookmark-star-fill',       'texto' => 'Criterios de Evaluación']
            ];
            break;
        case 'recomendaciones.php':
            $titulo_panel = "Sugerencias y Guías";
            $key_modulo = "lastTab_recomendaciones";
            $elementos_sidebar = [
                ['id' => 'enviar-tab',    'target' => '#panel-enviar',    'icono' => 'bi-chat-left-text',   'texto' => 'Enviar Nota'],
                ['id' => 'banco-tab',     'target' => '#brand-banco',     'icono' => 'bi-journal-check',    'texto' => 'Banco de Estrategias'],
                ['id' => 'filtros-tab',   'target' => '#panel-filtros',   'icono' => 'bi-funnel-fill',      'texto' => 'Búsqueda por Grado'],
                ['id' => 'guias-tab',     'target' => '#panel-guias',     'icono' => 'bi-lightbulb-fill',   'texto' => 'Guías de Adecuación']
            ];
            break;
    }

    if (!empty($elementos_sidebar)): ?>
    <nav id="sidebar">
        <ul class="list-unstyled components nav flex-column" id="dynamicTabsList" role="tablist">
            <li>
                <a href="#dynamicSubmenu" data-bs-toggle="collapse" aria-expanded="true" class="dropdown-toggle text-white d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-grid-1x2-fill me-2"></i> <?php echo $titulo_panel; ?></span>
                </a>
                <ul class="collapse show list-unstyled ps-3 mt-2" id="dynamicSubmenu">
                    <?php foreach ($elementos_sidebar as $item): ?>
                    <li class="nav-item mb-1">
                        <button class="nav-link w-100 text-start bg-transparent border-0 side-tab-btn" 
                                id="<?php echo $item['id']; ?>" data-bs-toggle="tab" data-bs-target="<?php echo $item['target']; ?>" 
                                type="button" role="tab" aria-controls="<?php echo substr($item['target'], 1); ?>" aria-selected="false">
                            <i class="bi <?php echo $item['icono']; ?> me-2"></i> <?php echo $item['texto']; ?>
                        </button>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </li>
        </ul>
        <div class="sidebar-footer text-center p-3">
            <i class="bi bi-person-circle fs-2 text-white-50"></i>
            <p class="mb-1 small text-white fw-bold text-uppercase"><?php echo $_SESSION['rol'] ?? 'Invitado'; ?></p>
            <a href="../servidor/logout.php" class="btn btn-sm btn-danger w-100 mt-2 btn-sidebar-logout">Cerrar Sesión</a>
        </div>
    </nav>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const keyModulo = "<?php echo $key_modulo; ?>";
            if (!keyModulo) return;
            const ultimaPestanaId = localStorage.getItem(keyModulo) || document.querySelector('.side-tab-btn').id;
            const botonActivar = document.getElementById(ultimaPestanaId);
            if (botonActivar) {
                botonActivar.classList.add("active");
                const panelActivar = document.querySelector(botonActivar.getAttribute("data-bs-target"));
                if (panelActivar) panelActivar.classList.add("show", "active");
            }
            document.querySelectorAll('.side-tab-btn').forEach(b => b.addEventListener('click', () => localStorage.setItem(keyModulo, b.id)));
        });
    </script>
    <?php endif; ?>

    <div id="content">
<style>
  .menu-link:hover { background-color: rgba(255, 255, 255, 0.1); border-radius: 5px; transform: translateY(-2px); color: #edc1e0fd !important; }
  .menu-link.active-link { background-color: rgba(255, 255, 255, 0.1); border-radius: 5px; color: #fff !important; font-weight: 600; border-bottom: 3px solid #c5005a; }
  .btn-logout { background-color: #c5005a; color: white; border-radius: 8px; padding: 7px 20px; font-weight: bold; border: 2px solid transparent; transition: all 0.3s ease; text-decoration: none; font-size: 0.85rem; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); }
  .btn-logout:hover { background-color: white; color: #c5005a; border-color: #c5005a; transform: scale(1.05); }
  .wrapper { display: flex; width: 100%; align-items: stretch; min-height: calc(100vh - 60px); }
  #sidebar { min-width: 260px; max-width: 260px; background: #54063e; color: #fff; display: flex; flex-direction: column; justify-content: space-between; }
  .side-tab-btn { padding: 12px 15px; font-size: 0.95rem; color: #ffc2e4 !important; border-radius: 6px; transition: 0.2s; }
  .side-tab-btn:hover { color: #fff !important; background: rgba(255, 255, 255, 0.1); }
  .side-tab-btn.active { color: #fff !important; background-color: #c5005a !important; font-weight: bold; }
  .sidebar-footer { background: rgba(0, 0, 0, 0.15); border-top: 1px solid rgba(255, 255, 255, 0.1); }
  #content { width: 100%; padding: 30px; background-color: #f7f1f5; }
</style>