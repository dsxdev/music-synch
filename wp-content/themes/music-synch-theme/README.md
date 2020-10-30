# Readme WP-Stars Understrap Child #
//https://guides.github.com/features/mastering-markdown/

1. **SCSS** 
Wir benutzen SCSS. Projekt SCSS wird in _project.scss geschrieben. 
Variabel in _projectvariables.scss.
Neue SCSS Files werden in main.scss eingefügt.
    1.1 **SCSSPHP**
    Wir benutzen ein Script, das SCSS am Server compiled "scssphp" von Leafo.
    Watcher fürs Compilen von Scss wird in functions.php in der Funktion wps_enqueue_styles() mit check_for_recompile('_project.scss',true) erweitert.
    Wenn das File in main.scss includet ist, muss es nicht enqueued werden. Wenn nicht, dann muss es an obriger Stelle enqueued werden.
    1.2 **CSS** 
    CSS verwenden wir nicht. Das einzige Mal, wenn wir etwas in css machen, ist das, die *Theme Beschreibung usw zu ändern in style.css*
    1.3 **BOOTSTRAP**
    Understrap verwendet Bootstrap. 
    1.4 **UNDERSTRAP**
    Understrap Variabeln können in _projectvariables.scss geändert werden.

2. **JS**
In main.js ist das JS bzw JQuery vom WP-Stars Understrap Child. Projekt-JS ist in project.js zu schreiben.

4. **MENU**
Das menü verwendet wen Navwalker von Understrap: bootstrap-wp-navwalker.php - dieser kann ummodelliert werden, sodass der erste Menüpunkt klickbar ist oder nicht.

3. **ERWEITERUNGEN**
Jegliche Erweiterungen sind sofern möglich, projektunabhängig zu programmieren und in wps-modules zu speichern.
