// external js: isotope.pkgd.js

// store filter for each group
var filters = {};

// init Isotope
var $grid = $('.isotope .grid').isotope({
    itemSelector: '.element-item',
    layoutMode: 'packery',
    packery: {
        gutter: 20,
    },
    getSortData: {
        date: '[data-date]'
    },
    // sort by color then number
    sortBy: 'date',
    sortAscending: false
});

$grid.on( 'arrangeComplete', function( event, filteredItems ) {
    var elems = $grid.isotope('getItemElements');
    $(elems).each(function(){
       $(this).removeClass('hidden');
    });
});


$('.isotope .filters').on( 'click', 'button', function() {
    var $this = $(this);
    // get group key
    var $buttonGroup = $this.parents('.button-group');
    var filterGroup = $buttonGroup.attr('data-filter-group');

    $this.closest('.button-group').find('li').removeClass("active");
    var $buttonLi = $this.parents('li');
    $buttonLi.addClass("active");

    // set filter for group
    filters[ filterGroup ] = $this.attr('data-filter');
    // combine filters
    var filterValue = concatValues( filters );
    // set filter for Isotope
    $grid.isotope({ filter: filterValue });
});

// change is-checked class on buttons
$('.isotope .button-group').each( function( i, buttonGroup ) {
    var $buttonGroup = $( buttonGroup );
    $buttonGroup.on( 'click', 'button', function() {
        $buttonGroup.find('.is-checked').removeClass('is-checked');
        $( this ).addClass('is-checked');
    });
});

// flatten object by concatting values
function concatValues( obj ) {
    var value = '';
    for ( var prop in obj ) {
        value += obj[ prop ];
    }
    return value;
}

$(document).ready(function() {
    var $filtersChecked = $('.isotope .filters').find('button.is-checked');
    $filtersChecked.each(function () {
        $(this).trigger('click');
    });

    if(window.location.hash) {
        //$('.filters').find('button[data-filter=".user_'+window.location.hash.replace('#','')+'"]').trigger('click');
        $('.filters').find('button[data-fullname="'+window.location.hash.replace('#','')+'"]').trigger('click');
    }
});
