/*
 * DIVM · Formularios: nombres claros, descripción de cada campo, validación en el navegador
 * y ventana de confirmación al editar o eliminar. Se carga una sola vez desde layouts/app.blade.php.
 *
 * Para cambiar un texto basta editar el diccionario CAMPOS de abajo
 * (l = etiqueta, h = texto de ayuda, p = ejemplo dentro del campo, a = atributos HTML).
 */
(function () {
    'use strict';

    var hoy = new Date(); hoy = new Date(hoy.getTime() - hoy.getTimezoneOffset() * 60000).toISOString().slice(0, 10);
    var NOMBRE = /^[\p{L}\p{M}][\p{L}\p{M}\s.'’\-]*$/u;

    var AREA = 'Máximo 5,000 caracteres.';
    var CAMPOS = {

        registro: {
            nombre:           { l: 'Nombre completo', h: 'Escríbelo aquí una sola vez; en los demás módulos se elige de una lista. Sin números.', p: 'Nombre y apellidos', a: { maxlength: 150, minlength: 3 }, k: 'nombre' },
            edad:             { l: 'Edad (años)', h: 'Años cumplidos, de 0 a 120.', a: { min: 0, max: 120 } },
            fecha_nacimiento: { l: 'Fecha de nacimiento', h: 'Día, mes y año. No puede ser una fecha futura.', a: { max: hoy, min: '1900-01-01' } },
            calle:            { l: 'Calle y número', h: 'Ejemplo: Av. Juárez 123.', p: 'Ej. Av. Juárez 123', a: { maxlength: 150 } },
            cp:               { l: 'Código postal', h: '5 dígitos.', p: '00000', a: { maxlength: 5, inputmode: 'numeric', pattern: '[0-9]{5}', title: 'El código postal tiene 5 números.' }, k: 'digitos' },
            colonia:          { l: 'Colonia', a: { maxlength: 100 } },
            municipio:        { l: 'Municipio', a: { maxlength: 100 } },
            referencias:      { l: 'Referencias', h: 'Entre qué calles está, color de la casa, etc.', a: { maxlength: 255 } },
            direccion:        { l: 'Domicilio', h: 'Calle, número, colonia y municipio.', a: { maxlength: 500 } },
            escolarizado:     { l: '¿Asiste a la escuela?', h: 'Elige Sí si actualmente va a una escuela o estancia.' },
            diagnostico:      { l: 'Diagnóstico', h: 'Diagnóstico o sospecha (ej. autismo, TDAH, retraso del lenguaje).', a: { maxlength: 150 } },
            motivo_consulta:  { l: 'Motivo de consulta', h: 'Por qué la familia pide atención. Máximo 1,000 caracteres.', a: { maxlength: 1000 } },
            madre:            { l: 'Madre o tutora', h: 'Nombre completo. Se copia solo a Planes, Recomendaciones e Informes.', a: { maxlength: 150 }, k: 'nombre' },
            padre:            { l: 'Padre o tutor', h: 'Nombre completo. Se copia solo a Planes, Recomendaciones e Informes.', a: { maxlength: 150 }, k: 'nombre' }
        },

        plan: {
            madre: { l: 'Madre o tutora', a: { maxlength: 150 }, k: 'nombre' },
            padre: { l: 'Padre o tutor', a: { maxlength: 150 }, k: 'nombre' },
            terapia_comunicativa:           { l: 'Comunicación y lenguaje', h: 'Actividades para que la familia practique en casa. ' + AREA, a: { maxlength: 5000 } },
            terapia_sensorial:              { l: 'Integración sensorial', h: 'Estímulos y juegos sensoriales recomendados. ' + AREA, a: { maxlength: 5000 } },
            terapia_aprendizaje:            { l: 'Aprendizaje', h: 'Ejercicios de lectura, escritura, números, etc. ' + AREA, a: { maxlength: 5000 } },
            terapia_ocupacional:            { l: 'Terapia ocupacional', h: 'Autonomía y rutinas diarias (vestirse, comer, higiene). ' + AREA, a: { maxlength: 5000 } },
            terapia_atencion:               { l: 'Atención y concentración', h: 'Ejercicios para mantener la atención. ' + AREA, a: { maxlength: 5000 } },
            terapia_atencion_concentracion: { l: 'Atención y concentración', h: 'Ejercicios para mantener la atención. ' + AREA, a: { maxlength: 5000 } },
            terapia_emocional:              { l: 'Educación emocional', h: 'Manejo de emociones y conducta. ' + AREA, a: { maxlength: 5000 } },
            terapia_educacion_emocional:    { l: 'Educación emocional', h: 'Manejo de emociones y conducta. ' + AREA, a: { maxlength: 5000 } }
        },

        recomendaciones: {
            madre:               { l: 'Madre o tutora', a: { maxlength: 150 }, k: 'nombre' },
            padre:               { l: 'Padre o tutor', a: { maxlength: 150 }, k: 'nombre' },
            escuela_procedencia: { l: 'Escuela de procedencia', h: 'Escuela a la que va dirigida la nota.', a: { maxlength: 255 } },
            grado:               { l: 'Nivel educativo', h: 'Sirve para filtrar el historial por nivel.' },
            recomendacion:       { l: 'Recomendación pedagógica', h: 'Lo que la escuela puede hacer en el aula. Lenguaje sencillo y concreto. Máximo 5,000 caracteres.', a: { maxlength: 5000, minlength: 3 } },
            notas:               { l: 'Notas internas', h: 'Solo las ve el personal; no se envían a la escuela. Máximo 2,000 caracteres.', a: { maxlength: 2000 } }
        },

        informe: {
            nombre_padre:      { h: 'Nombre completo del padre o tutor.', a: { maxlength: 150 }, k: 'nombre' },
            nombre_madre:      { h: 'Nombre completo de la madre o tutora.', a: { maxlength: 150 }, k: 'nombre' },
            edad_padre:        { h: 'Años cumplidos (12 a 100).', a: { min: 12, max: 100 } },
            edad_madre:        { h: 'Años cumplidos (12 a 100).', a: { min: 12, max: 100 } },
            fecha_nacimiento:  { l: 'Fecha de nacimiento', h: 'No puede ser una fecha futura.', a: { max: hoy, min: '1900-01-01' } },
            prueba_aplicada:   { l: 'Prueba aplicada', h: 'Nombre de la prueba o escala usada.', a: { maxlength: 255 } },
            aplicadora:        { l: 'Quién aplicó la prueba', h: 'Nombre del especialista.', a: { maxlength: 255 } },
            motivo_evaluacion: { l: 'Motivo de la evaluación', h: 'Por qué se evalúa al paciente. Máximo 3,000 caracteres.', a: { maxlength: 3000 } },
            ind_clinicas:      { h: 'Indicaciones médicas o clínicas. ' + AREA, a: { maxlength: 5000 } },
            ind_familiares:    { h: 'Pautas para la familia. ' + AREA, a: { maxlength: 5000 } },
            ind_escolares:     { h: 'Indicaciones para la escuela. ' + AREA, a: { maxlength: 5000 } },
            ind_rehabilitadores: { h: 'Terapias de apoyo recomendadas. ' + AREA, a: { maxlength: 5000 } }
        },

        valoracion: {
            paciente_id:       { h: 'Elige al paciente de la lista. Si no aparece, primero regístralo en Pacientes.' },
            prueba_aplicada:   { l: 'Prueba aplicada', h: 'Nombre de la prueba o escala usada (ej. Escala Wechsler).', a: { maxlength: 255 } },
            aplicadora:        { l: 'Quién aplicó la prueba', h: 'Nombre del especialista que evaluó.', a: { maxlength: 255 } },
            motivo_evaluacion: { l: 'Motivo de la evaluación', h: 'Por qué se evalúa al paciente. Máximo 3,000 caracteres.', a: { maxlength: 3000 } },
            hc_gestacion:      { l: 'Embarazo y parto', h: 'Semanas, complicaciones, tipo de parto.', a: { maxlength: 5000 } },
            hc_talla_nacer:    { l: 'Peso y talla al nacer', h: 'Ejemplo: 3.200 kg y 50 cm.', a: { maxlength: 100 } },
            hc_diagnostico:    { l: 'Diagnóstico', h: 'Presuntivo o definitivo. ' + AREA, a: { maxlength: 5000 } },
            des_cognitivo:     { l: 'Área cognitiva (pensamiento y memoria)', h: 'Atención, memoria y razonamiento. ' + AREA, a: { maxlength: 5000 } },
            des_motor:         { l: 'Área motriz (movimiento)', h: 'Motricidad fina y gruesa. ' + AREA, a: { maxlength: 5000 } },
            des_social:        { l: 'Área social y emocional', h: 'Cómo convive con otras personas. ' + AREA, a: { maxlength: 5000 } },
            res_lenguaje:      { l: 'Lenguaje y comunicación', h: 'Comprensión y expresión. ' + AREA, a: { maxlength: 5000 } },
            res_conducta_valoracion: { l: 'Conducta durante la evaluación', h: 'Cómo se comportó en las pruebas. ' + AREA, a: { maxlength: 5000 } },
            ind_escolares:     { l: 'Indicaciones para la escuela', h: 'Adecuaciones en el aula. ' + AREA, a: { maxlength: 5000 } },
            ind_clinicas:      { l: 'Indicaciones clínicas', h: 'Indicaciones médicas. ' + AREA, a: { maxlength: 5000 } },
            ind_familiares:    { l: 'Indicaciones para la familia', h: 'Pautas de crianza y rutinas en casa. ' + AREA, a: { maxlength: 5000 } },
            ind_rehabilitadores: { l: 'Indicaciones de rehabilitación', h: 'Terapias físicas, de lenguaje u ocupacionales. ' + AREA, a: { maxlength: 5000 } }
        },

        avances: {
            paciente_id:      { h: 'Elige al paciente de la lista.' },
            fecha_sesion:     { l: 'Fecha de la sesión', h: 'Día en que se dio la sesión. No puede ser futura.', a: { max: hoy, min: '2000-01-01' } },
            hora_inicio:      { l: 'Hora de inicio', h: 'Formato de 24 horas, ej. 16:00.' },
            hora_fin:         { l: 'Hora de fin', h: 'Debe ser posterior a la hora de inicio.' },
            estatus_sesion:   { l: 'Asistencia', h: 'Si no asistió, elige el tipo de falta.' },
            actividades_realizadas:       { l: '1. Actividades realizadas', h: 'Dinámicas, materiales y adecuaciones. Máximo 3,000 caracteres.', a: { maxlength: 3000 } },
            observaciones_comportamiento: { l: '2. Conducta observada', h: 'Tolerancia a la frustración, atención, respuesta a estímulos. Máximo 3,000 caracteres.', a: { maxlength: 3000 } },
            logros_obtenidos:             { l: '3. Logros de la sesión', h: 'Objetivos del plan que se alcanzaron hoy. Máximo 3,000 caracteres.', a: { maxlength: 3000 } },
            tareas_o_recomendaciones:     { l: '4. Tareas para casa', h: 'Actividades sugeridas para los tutores. Máximo 3,000 caracteres.', a: { maxlength: 3000 } }
        },

        usuarios: {
            nombre:    { l: 'Nombre(s)', h: 'Solo letras y espacios.', a: { maxlength: 100, minlength: 2 }, k: 'nombre' },
            apaterno:  { l: 'Apellido paterno', a: { maxlength: 100, minlength: 2 }, k: 'nombre' },
            amaterno:  { l: 'Apellido materno', a: { maxlength: 100, minlength: 2 }, k: 'nombre' },
            correo:    { l: 'Correo electrónico', h: 'Con este correo inicia sesión. Debe ser único.', a: { maxlength: 100, autocomplete: 'email' } },
            contrasena:       { l: 'Contraseña', h: 'Mínimo 8 caracteres, con letras y números.', a: { minlength: 8, maxlength: 72, autocomplete: 'new-password' }, k: 'clave' },
            nueva_contrasena: { l: 'Nueva contraseña', h: 'Déjala vacía para conservar la actual. Mínimo 8 caracteres, con letras y números.', a: { minlength: 8, maxlength: 72, autocomplete: 'new-password' }, k: 'clave' },
            rol:       { l: 'Rol', h: 'Administrador puede editar, eliminar y gestionar usuarios; Personal solo registra y consulta.' },
            estado:    { l: 'Estado de la cuenta', h: 'Una cuenta Inactiva no puede iniciar sesión.' }
        },

        distribucion: {
            paciente:    { l: 'Paciente', h: 'Se elige de la lista de pacientes activos.' },
            id_usuario:  { l: 'Terapeuta', h: 'Persona que atenderá al paciente.' },
            fecha:       { l: 'Fecha', h: 'Día exacto del horario. De lunes a sábado.' },
            hora_inicio: { l: 'Hora de inicio', h: 'Formato de 24 horas, de 07:00 a 19:59.', a: { min: '07:00', max: '19:59' } },
            hora_fin:    { l: 'Hora de fin', h: 'Debe ser posterior a la hora de inicio y no pasar de las 20:00.', a: { min: '07:01', max: '20:00' } }
        }
    };

    // Títulos de secciones y pestañas con nombres más claros (texto exacto actual → texto nuevo)
    var TEXTOS = {
        'Datos Generales': 'Datos del paciente', 'Dirección Completa': 'Domicilio',
        'Información de los Padres': 'Madre, padre o tutores', 'Pacientes Registrados': 'Lista de pacientes',
        'Búsqueda de Pacientes': 'Buscar paciente', 'Altas / Bajas (Estatus)': 'Activar o dar de baja',
        'Registrar Paciente': 'Nuevo paciente', 'Consultar Historial': 'Lista de pacientes', 'Búsqueda y Filtros': 'Buscar y filtrar',
        'Nuevo Plan': 'Nuevo plan de trabajo', 'Planes Registrados': 'Planes guardados',
        'Identificación del Paciente': 'Datos del paciente', 'Áreas de Intervención Especializada': 'Áreas de trabajo',
        'Identificación del Alumno': 'Datos del paciente', 'Información Académica': 'Escuela y nivel',
        'Detalles de la Sugerencia': 'Recomendación', 'Enviar Nota': 'Nueva recomendación',
        'Banco de Estrategias': 'Estrategias sugeridas', 'Búsqueda por Grado': 'Buscar por nivel',
        'Recomendaciones Escolares Emitidas': 'Recomendaciones guardadas',
        'Filtrar Historial por Nivel Educativo': 'Filtrar por nivel educativo',
        'Datos de Identificación': 'Datos del paciente y la familia', 'Evaluación y Motivo': 'Evaluación',
        'Historia Clínica y Resultados': 'Antecedentes y resultados', 'Desarrollo y Áreas': 'Áreas de desarrollo',
        'Indicaciones Finales': 'Indicaciones y recomendaciones', 'Generar Informe': 'Nuevo informe',
        'Informes Registrados': 'Informes guardados',
        'Asignación del Paciente': 'Paciente evaluado', '1. Datos de Evaluación Inicial': '1. Datos de la evaluación',
        '2. Antecedentes Médicos e Historia Clínica': '2. Antecedentes médicos',
        '3. Resultados del Análisis por Áreas de Desarrollo': '3. Resultados por área',
        '4. Indicaciones y Líneas de Acción Recomendadas': '4. Indicaciones y acciones recomendadas',
        'Registrar Informe': 'Nueva valoración', 'Historial de Informes de Valoración Emitidos': 'Valoraciones guardadas',
        'Datos de Control': 'Datos de la sesión', 'Evaluación Cualitativa e Intervención': 'Qué se hizo y cómo respondió',
        'Registrar Avance': 'Nueva sesión', 'Historial de Sesiones': 'Sesiones registradas',
        'Expediente Pedagógico': 'Expediente del paciente', 'Historial General de Intervenciones': 'Todas las sesiones',
        'Asignar Horario': 'Asignar horario',
        'Distribución de Horarios': 'Horarios asignados', 'Calendario Semanal': 'Semana de trabajo',
        'Registrar Usuario': 'Nuevo usuario', 'Lista de Usuarios': 'Usuarios registrados',
        'Datos Personales': 'Datos de la persona', 'Acceso al Sistema': 'Acceso al sistema',
        'Registrar Nuevo Usuario': 'Nuevo usuario del sistema'
    };

    var MODULO = (function () {
        var m = location.pathname.match(/\/(registro|plan|recomendaciones|informe|valoracion|avances|usuarios|distribucion|crear-cuenta)(?:\/|$)/);
        return m ? (m[1] === 'crear-cuenta' ? 'usuarios' : m[1]) : null;
    })();

    function soloTexto(el) {
        var t = ''; el.childNodes.forEach(function (n) { if (n.nodeType === 3) t += n.nodeValue; });
        return t.replace(/\s+/g, ' ').trim();
    }

    function ponerTexto(el, nuevo) {
        var conDosPuntos = /:\s*$/.test(soloTexto(el));
        var primero = null;
        el.childNodes.forEach(function (n) { if (n.nodeType === 3 && n.nodeValue.trim() && !primero) primero = n; });
        var valor = ' ' + nuevo + (conDosPuntos ? ':' : '') + ' ';
        if (primero) {
            primero.nodeValue = valor;
            el.childNodes.forEach(function (n) { if (n !== primero && n.nodeType === 3 && n.nodeValue.trim()) n.nodeValue = ' '; });
        } else { el.appendChild(document.createTextNode(valor)); }
    }

    function etiquetaDe(ctl) {
        var grupo = ctl.closest('.input-group');
        if (grupo) return grupo.querySelector('.input-group-text');
        var cont = ctl.parentElement;
        var n = cont ? cont.querySelectorAll('input:not([type=hidden]), select, textarea').length : 0;
        if (n !== 1) return null; // el rótulo es de un grupo de campos: no se toca
        return cont.querySelector('label, .area-label, .etiqueta-terapia');
    }

    function aplicarCampos() {
        var dic = CAMPOS[MODULO]; if (!dic) return;
        Object.keys(dic).forEach(function (nombre) {
            var d = dic[nombre];
            document.querySelectorAll('[name="' + nombre + '"]').forEach(function (ctl) {
                if (ctl.type === 'hidden' || ctl.closest('#formFiltros')) return;
                if (d.l) { var et = etiquetaDe(ctl); if (et) ponerTexto(et, d.l); }
                if (d.p && ctl.placeholder !== undefined && !ctl.matches('select')) ctl.placeholder = d.p;
                if (d.a) Object.keys(d.a).forEach(function (a) { if (!ctl.hasAttribute(a)) ctl.setAttribute(a, d.a[a]); });
                if (d.k) ctl.dataset.tipo = d.k;
                if (d.h && !ctl.dataset.ayudaOk) {
                    ctl.dataset.ayudaOk = '1';
                    var ayuda = document.createElement('div');
                    ayuda.className = 'form-text campo-ayuda';
                    ayuda.innerHTML = '<i class="bi bi-info-circle me-1"></i>';
                    ayuda.appendChild(document.createTextNode(d.h));
                    var ref = ctl.closest('.input-group') || ctl;
                    ref.parentNode.insertBefore(ayuda, ref.nextSibling);
                }
            });
        });
    }

    function aplicarTextos() {
        document.querySelectorAll('.section-label, .side-tab-btn, .titulo-panel h3, h4.etiqueta-seccion').forEach(function (el) {
            var t = soloTexto(el);
            if (TEXTOS[t]) ponerTexto(el, TEXTOS[t]);
        });
    }


    /* ---------- Encabezado igual en todos los formularios (icono, título y subtítulo) ---------- */
    var SUBTITULOS = {
        'Expediente del Paciente': ['bi-file-earmark-person', 'Datos generales, domicilio y familia de cada paciente'],
        'Plan de Intervención para Padres': ['bi-journal-text', 'Actividades para que la familia practique en casa con el paciente'],
        'Registrar Recomendación Escolar': ['bi-chat-left-text', 'Sugerencias para que la escuela apoye al paciente en el aula'],
        'Registro de Informe Pedagógico': ['bi-file-earmark-pdf', 'Resumen del desempeño y las indicaciones para la familia y la escuela'],
        'Registrar Informe de Valoración': ['bi-file-earmark-medical', 'Resultados de la evaluación inicial por áreas de desarrollo'],
        'Registrar Nuevo Usuario': ['bi-person-plus-fill', 'Alta de personal con acceso al sistema'],
        'Roles y Permisos': ['bi-shield-lock-fill', 'Qué puede hacer cada rol dentro del sistema'],
        'Asignar Horario': ['bi-calendar-plus', 'Define qué terapeuta atiende a cada paciente, qué día y en qué horario'],
        'Registro de Evolución y Avances Diarios': ['bi-clipboard-pulse', 'Seguimiento psicopedagógico individualizado para pacientes con discapacidad'],
        'Modificar Expediente del Paciente': ['bi-pencil-square', 'Corrige los datos y guarda los cambios'],
        'Modificar Plan de Intervención': ['bi-pencil-square', 'Corrige las actividades y guarda los cambios'],
        'Modificar Informe Pedagógico': ['bi-pencil-square', 'Corrige el informe y guarda los cambios'],
        'Modificar Recomendación Escolar': ['bi-pencil-square', 'Corrige la recomendación y guarda los cambios'],
        'Actualizar Información de Usuario': ['bi-pencil-square', 'Cambia los datos de la persona y guarda los cambios']
    };

    function aplicarEncabezados() {
        document.querySelectorAll('main .titulo-banner h3, main .titulo-panel h3, main h2.titulo-principal, main h3.text-center').forEach(function (h) {
            var clave = soloTexto(h), info = SUBTITULOS[clave];
            if (!info || h.dataset.encabezado) return;
            h.dataset.encabezado = '1';
            h.style.color = ''; h.classList.add('titulo-formulario');
            if (!h.querySelector('i')) { var i = document.createElement('i'); i.className = 'bi ' + info[0] + ' me-2'; h.insertBefore(i, h.firstChild); }
            var cont = h.parentElement, ya = cont.querySelector(':scope > p.subtitulo-formulario, :scope > p.small');
            if (!ya) {
                var p = document.createElement('p');
                p.className = 'subtitulo-formulario mb-3';
                p.textContent = info[1];
                h.insertAdjacentElement('afterend', p);
            }
        });
    }

    /* ---------- Siempre "paciente" (nunca "alumno") ---------- */
    function aPaciente() {
        var raiz = document.querySelector('main') || document.body;
        var w = document.createTreeWalker(raiz, NodeFilter.SHOW_TEXT, null), n, lista = [];
        while ((n = w.nextNode())) { if (/alumn[oa]s?/i.test(n.nodeValue) && !/^(SCRIPT|STYLE|TEXTAREA)$/.test(n.parentNode.nodeName)) lista.push(n); }
        lista.forEach(function (t) {
            t.nodeValue = t.nodeValue
                .replace(/Seleccionar Alumno/g, 'Paciente')
                .replace(/Seleccione el alumno/g, 'Seleccione al paciente')
                .replace(/\bAlumnos\b/g, 'Pacientes').replace(/\balumnos\b/g, 'pacientes')
                .replace(/\bAlumno\b/g, 'Paciente').replace(/\balumno\b/g, 'paciente')
                .replace(/\bAlumnas\b/g, 'Pacientes').replace(/\balumnas\b/g, 'pacientes')
                .replace(/\bAlumna\b/g, 'Paciente').replace(/\balumna\b/g, 'paciente');
        });
        document.querySelectorAll('main option').forEach(function (o) {
            if (/alumn/i.test(o.textContent) && !o.value) o.textContent = o.textContent.replace(/el alumno/i, 'al paciente').replace(/alumno/i, 'paciente');
        });
    }

    /* ---------- Solo se puede escribir lo que corresponde a cada campo ---------- */
    var FILTROS = {
        nombre:   /[^\p{L}\p{M}\s.'’\-]/gu,   // sin números ni símbolos
        digitos:  /\D/g,                         // solo números
        telefono: /[^0-9+\-\s()]/g
    };

    function restringir(ctl) {
        var f = FILTROS[ctl.dataset.tipo];
        if (f) {
            ctl.addEventListener('input', function () {
                var limpio = ctl.value.replace(f, '');
                if (limpio !== ctl.value) { var pos = ctl.selectionStart - (ctl.value.length - limpio.length); ctl.value = limpio; try { ctl.setSelectionRange(pos, pos); } catch (e) {} }
            });
        }
        if (ctl.type === 'number') {
            ctl.addEventListener('keydown', function (e) { if (['e', 'E', '+', '-', '.', ','].indexOf(e.key) > -1) e.preventDefault(); });
            ctl.addEventListener('input', function () {
                var v = ctl.value; if (v === '') return;
                if (ctl.max !== '' && +v > +ctl.max) ctl.value = ctl.max;
                if (ctl.min !== '' && +v < +ctl.min && v.length >= String(ctl.min).length + 1) ctl.value = ctl.min;
            });
        }
        if (ctl.matches('input[type=date]')) { ctl.addEventListener('keydown', function (e) { if (e.key.length === 1 && /\D/.test(e.key)) e.preventDefault(); }); }
    }

    /* ---------- Validación en el navegador (en español) ---------- */
    function mensaje(ctl) {
        var v = ctl.validity;
        if (v.valueMissing) return ctl.matches('select') ? 'Elige una opción de la lista.' : 'Este campo es obligatorio.';
        if (v.typeMismatch) return ctl.type === 'email' ? 'Escribe un correo válido, por ejemplo nombre@correo.com.' : 'El formato no es válido.';
        if (v.tooShort) return 'Escribe al menos ' + ctl.minLength + ' caracteres.';
        if (v.tooLong) return 'No puede pasar de ' + ctl.maxLength + ' caracteres.';
        if (v.rangeUnderflow) return ctl.type === 'date' ? 'La fecha es demasiado antigua o anterior a hoy.' : 'El valor mínimo es ' + ctl.min + '.';
        if (v.rangeOverflow) return ctl.type === 'date' ? 'La fecha no puede ser posterior a ' + ctl.max.split('-').reverse().join('/') + '.' : 'El valor máximo es ' + ctl.max + '.';
        if (v.patternMismatch) return ctl.title || 'El formato no es válido.';
        if (v.badInput) return 'Escribe un valor válido.';
        return ctl.validationMessage;
    }

    function reglasPropias(ctl) {
        var v = ctl.value.trim(), tipo = ctl.dataset.tipo;
        ctl.setCustomValidity('');
        if (!v) return;
        if (tipo === 'nombre' && !NOMBRE.test(v)) ctl.setCustomValidity('Solo letras, espacios, puntos y guiones (sin números).');
        if (tipo === 'clave' && !/^(?=.*[A-Za-z])(?=.*\d).+$/.test(v)) ctl.setCustomValidity('Debe incluir al menos una letra y un número.');
    }

    function horas(form) {
        var i = form.querySelector('[name="hora_inicio"]'), f = form.querySelector('[name="hora_fin"]');
        if (!i || !f) return;
        f.setCustomValidity(i.value && f.value && f.value <= i.value ? 'La hora de fin debe ser posterior a la de inicio.' : '');
    }

    function mostrarError(ctl) {
        var prev = ctl.parentNode.querySelector(':scope > .campo-error, :scope + .campo-error');
        var ref = ctl.closest('.input-group') || ctl;
        var sig = ref.nextElementSibling;
        if (sig && sig.classList.contains('campo-error')) sig.remove();
        if (!ctl.validity.valid) {
            var e = document.createElement('div');
            e.className = 'campo-error';
            e.textContent = mensaje(ctl);
            ref.parentNode.insertBefore(e, ref.nextSibling);
            ctl.classList.add('is-invalid');
        } else { ctl.classList.remove('is-invalid'); }
    }

    function conectarValidacion(form) {
        if (form.dataset.validacion) return; form.dataset.validacion = '1';
        form.setAttribute('novalidate', 'novalidate');
        var campos = form.querySelectorAll('input:not([type=hidden]):not([type=submit]), select, textarea');
        campos.forEach(function (c) {
            restringir(c);
            c.addEventListener('input', function () { reglasPropias(c); horas(form); if (c.classList.contains('is-invalid')) mostrarError(c); });
            c.addEventListener('blur', function () { reglasPropias(c); horas(form); if (c.value) mostrarError(c); });
            reglasPropias(c);
        });
        form.addEventListener('submit', function (ev) {
            campos.forEach(reglasPropias); horas(form);
            var malos = Array.prototype.filter.call(campos, function (c) { return !c.disabled && !c.validity.valid; });
            if (malos.length) {
                ev.preventDefault(); ev.stopImmediatePropagation();
                campos.forEach(mostrarError);
                malos[0].scrollIntoView({ behavior: 'smooth', block: 'center' });
                malos[0].focus({ preventScroll: true });
                return;
            }
        }, true);
    }

    /* ---------- Confirmación al editar o eliminar ---------- */
    var modal = null, pendiente = null;

    function crearModal() {
        if (modal) return;
        var d = document.createElement('div');
        d.className = 'modal fade'; d.id = 'modalConfirmar'; d.tabIndex = -1;
        d.innerHTML = '<div class="modal-dialog modal-dialog-centered"><div class="modal-content">' +
            '<div class="modal-header"><h5 class="modal-title" id="confTitulo"></h5><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button></div>' +
            '<div class="modal-body"><p id="confTexto" class="mb-1"></p><p id="confRegistro" class="small text-muted mb-0"></p></div>' +
            '<div class="modal-footer"><button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>' +
            '<button type="button" class="btn" id="confSi"></button></div></div></div>';
        document.body.appendChild(d);
        modal = new bootstrap.Modal(d);
        document.getElementById('confSi').addEventListener('click', function () {
            var f = pendiente; pendiente = null; modal.hide();
            if (f) { f.dataset.confirmado = '1'; if (f.requestSubmit) f.requestSubmit(); else f.submit(); }
        });
    }

    function preguntar(form) {
        crearModal();
        var met = (form.querySelector('input[name="_method"]') || {}).value || '';
        met = met.toUpperCase();
        var elimina = met === 'DELETE';
        var estatus = /\/estatus$/.test(form.getAttribute('action') || '');
        var fila = form.closest('tr');
        var nombre = fila ? soloTexto(fila.querySelector('td:nth-child(2)') || fila.querySelector('td')) || (fila.querySelector('td') || {}).textContent : '';
        document.getElementById('confTitulo').textContent = elimina ? 'Eliminar registro' : (estatus ? 'Cambiar estatus' : 'Guardar cambios');
        document.getElementById('confTexto').textContent = elimina
            ? '¿Seguro que quieres eliminarlo? Esta acción no se puede deshacer.'
            : (estatus ? '¿Seguro que quieres cambiar el estatus del paciente?' : 'Se reemplazará la información anterior por la que acabas de escribir. ¿Guardar los cambios?');
        document.getElementById('confRegistro').textContent = nombre ? 'Registro: ' + String(nombre).replace(/\s+/g, ' ').trim().slice(0, 80) : '';
        var si = document.getElementById('confSi');
        si.textContent = elimina ? 'Sí, eliminar' : 'Sí, guardar';
        si.className = 'btn ' + (elimina ? 'btn-danger' : 'btn-divm');
        pendiente = form; modal.show();
    }

    function conectarConfirmacion(form) {
        var m = form.querySelector('input[name="_method"]');
        var metodo = m ? m.value.toUpperCase() : '';
        if (metodo !== 'DELETE' && metodo !== 'PUT' && metodo !== 'PATCH') return;
        if (form.dataset.conf) return; form.dataset.conf = '1';
        form.removeAttribute('onsubmit'); // reemplaza el confirm() simple de antes
        form.addEventListener('submit', function (ev) {
            if (form.dataset.confirmado === '1') { form.dataset.confirmado = ''; return; }
            if (ev.defaultPrevented) return; // la validación ya detuvo el envío
            ev.preventDefault(); preguntar(form);
        });
    }

    function evitarDobleEnvio(form) {
        form.addEventListener('submit', function (ev) {
            if (ev.defaultPrevented) return;
            setTimeout(function () {
                if (ev.defaultPrevented) return;
                form.querySelectorAll('button[type=submit], input[type=submit]').forEach(function (b) { b.disabled = true; });
            }, 0);
        });
    }

    /* ---------- Solo se puede escribir lo que corresponde a cada campo ---------- */
    function restringir(ctl) {
        if (ctl.dataset.filtro) return; ctl.dataset.filtro = '1';
        var tipo = ctl.dataset.tipo, num = ctl.type === 'number' || ctl.getAttribute('inputmode') === 'numeric';
        if (tipo === 'nombre') {
            ctl.addEventListener('input', function () {
                var l = ctl.value.replace(/[^\p{L}\p{M}\s.'’\-]/gu, '').replace(/\s{2,}/g, ' ');
                if (l !== ctl.value) ctl.value = l;
            });
        }
        if (num) {
            ctl.addEventListener('keydown', function (e) {
                if (ctl.type === 'number' && /^[eE+\-.,]$/.test(e.key)) e.preventDefault();
            });
            ctl.addEventListener('input', function () {
                var l = ctl.value.replace(/\D/g, '');
                if (ctl.type !== 'number' && l !== ctl.value) ctl.value = l;
            });
        }
        if (ctl.matches('textarea, input[type=text]') && !tipo) {
            ctl.addEventListener('input', function () {
                var l = ctl.value.replace(/[<>\u0000-\u0008\u000B\u000C\u000E-\u001F]/g, '');
                if (l !== ctl.value) ctl.value = l;
            });
        }
    }

    function iniciar() {
        aplicarCampos(); aplicarTextos(); aplicarEncabezados(); aPaciente();
        document.querySelectorAll('main form, #content form').forEach(function (f) {
            if (f.method && f.method.toLowerCase() === 'get') return;
            f.querySelectorAll('input:not([type=hidden]):not([type=password]), textarea').forEach(restringir);
            conectarValidacion(f); conectarConfirmacion(f);
            if (!f.querySelector('input[name="_method"]') || true) evitarDobleEnvio(f);
        });
    }

    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', iniciar); else iniciar();
})();
