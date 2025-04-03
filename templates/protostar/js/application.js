//activa el comportamiento de los menus desplegables (dropdowns) de boostsrap
$('.dropdown-toggle').dropdown()
//expande los elementos colapsables con la clase "collapse"
$('.collapse').collapse('show')
//Oculta el modal con el ID "myModal"
$('#myModal').modal('hide')
//Activa la funcionalidad de autocompletado en los elementos con la clase "typehead"
//(Esta función se usaba en versiones antiguas de Bootstrap, pero ya no está disponible en Bootstrap 5)
$('.typeahead').typeahead()
// Aplica el estilo de botón a los elementos con la clase 'tabs'
// (Esta función se usaba en Bootstrap 2 y 3, pero ha sido eliminada en versiones más recientes)
$('.tabs').button()
// Activa los tooltips (descripciones emergentes) en los elementos con la clase 'tip'
$('.tip').tooltip()
// Activa la funcionalidad de alerta en los elementos con la clase 'alert-message'
$(".alert-message").alert()