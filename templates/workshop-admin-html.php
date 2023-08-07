<table id="workshops" class="cell-border stripe"></table>
<script>
jQuery(document).ready(function() {
  table = jQuery('#workshops').DataTable({
     "data":workshops,

	 "columns": [
		 { "data": "dates", "title":"Dates" },
		 { "data": "location","title":"Location" },
		 { "data": "price","title":"Price" },
		 { "data": "sale-price", class: 'editable sp',"title":"Sale Price" },
		 { "data": "stock-warning", class: 'editable sw',"title":"Stock" },
		 { "data": "dt","title":"Expiry Date"}
		 //{    
			 //edit button creation    
		//	 render: function (data, type, row) {    
		//		 return createButton('edit', row.id);    
		//	 }    
		 //}, 
    ],
	 "paging": false,
	 "searching": false,
	  "bInfo" : false,
	  createdRow: function( row, data, dataIndex ) {
    	if ( data['expired'] == "true" ) {
          jQuery(row).addClass( 'lightRed' );
        }
	  }
  });
    jQuery('#workshops tbody').on('click', 'button', function () {
        var data = table.row(jQuery(this).parents('tr')).data();
    });

});
function createButton(buttonType, rowID) {    
    var buttonText = buttonType == "edit" ? "Edit" : "Delete";    
    return '<button class="' + buttonType + '" type="button">' + buttonText+'</button>';    
}
jQuery('#workshops').on('click', 'tbody td .edit', function (e) {    
    fnResetControls();    
    var dataTable = jQuery('#workshops').DataTable();    
    var clickedRow = jQuery(jQuery(this).closest('td')).closest('tr');    
    jQuery(clickedRow).find('td').each(function () {    
        // do your cool stuff    
        if (jQuery(this).hasClass('editable')) {    
            if (jQuery(this).hasClass('sp')) {    
                var html = fnCreateTextBox(jQuery(this).html(), 'sp');    
                jQuery(this).html(jQuery(html))    
            }     
            if (jQuery(this).hasClass('sw')) {    
                var html = fnCreateTextBox(jQuery(this).html(), 'sw');    
                jQuery(this).html(jQuery(html))    
            }    
        }    
    });
    jQuery('#workshops tbody tr td .update').removeClass('update').addClass('edit').html('Edit');    
    jQuery('#workshops tbody tr td .cancel').removeClass('cancel').addClass('delete').html('Delete');    
    jQuery(clickedRow).find('td .edit').removeClass('edit').addClass('update').html('Update');    
    jQuery(clickedRow).find('td .delete').removeClass('delete').addClass('cancel').html('Cancel');    
    
});
jQuery('#workshops').on('click', 'tbody td .cancel', function (e) {    
	fnResetControls();    
	jQuery('#workshops tbody tr td .update').removeClass('update').addClass('edit').html('Edit');    
	jQuery('#workshops tbody tr td .cancel').removeClass('cancel').addClass('delete').html('Delete');    
});    
jQuery('#workshops').on('click', 'tbody td .update', function (e) {
	var sp = jQuery( "[data-field='sp']" ).val();
	var sw = jQuery( "[data-field='sw']" ).val();
	var openedTextBox = jQuery('#workshops').find('input');    
	jQuery.each(openedTextBox, function (k, $cell) {    
		fnUpdateDataTableValue($cell, $cell.value);    
		jQuery(openedTextBox[k]).closest('td').html($cell.value);    
	});
	
	var updateRow = jQuery(jQuery(this).closest('td')).closest('tr');  
	var data = jQuery('#workshops').DataTable().rows(updateRow).data()[0];
	console.log(data.wc_product_id);
	updateProduct(data.wc_product_id, sp, sw);
	jQuery('#workshops tbody tr td .update').removeClass('update').addClass('edit').html('Edit');    
	jQuery('#workshops tbody tr td .cancel').removeClass('cancel').addClass('delete').html('Delete');    
});    
    
function fnUpdateDataTableValue($inputCell, value) {    
	var dataTable = jQuery('#workshops').DataTable();    
	var rowIndex = dataTable.row(jQuery($inputCell).closest('tr')).index();    
	var fieldName = jQuery($inputCell).attr('data-field');    
	dataTable.rows().data()[rowIndex][fieldName] = value;    
}    
function fnResetControls() {    
	var openedTextBox = jQuery('#workshops').find('input');    
	jQuery.each(openedTextBox, function (k, $cell) {    
		jQuery(openedTextBox[k]).closest('td').html($cell.value);    
	})    
}
    
function fnCreateTextBox(value, fieldprop) {    
    return '<input data-field="' + fieldprop + '" type="text" value="' + value + '" ></input>';    
}
function updateProduct(variation_id, sale_price, stock_level){
	var rURL = "/wp-json/wc/v3/products/"+product_id+"/variations/"+variation_id+'?consumer_key=ck_fe7059d81d6fe1d437e5d83d1a4362df038323a3&consumer_secret=cs_21c75bc9ef996af52169718d6823954352138cb6';

	var updateWorkshop = {};
	if(sale_price){
		updateWorkshop.sale_price = sale_price;
	} else {
		updateWorkshop.sale_price = "";
	}
	if(stock_level){
		updateWorkshop.manage_stock = true;
		updateWorkshop.stock_quantity = stock_level;
	} else {
		updateWorkshop.manage_stock = false;
	}
	jQuery.post( rURL, updateWorkshop, function( responseData ) {});
	
}
</script>
