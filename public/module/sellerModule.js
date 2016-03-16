$(document).ready(function(){
  $("#optCashEx").on("click",function(event){
    $("#cashAmt").attr('disabled',false);
  });
  $("#optCashAll").on("click",function(event){
    $("#cashAmt").attr('disabled',true);
  });

	$('.dropdown-toggle').dropdown();
	//-------- BODY TOGGLE JS START------
    $(".container,header").on("click",function( event ){
        $( ".pullbox:visible" ).toggleClass('active', false);
    });

    $('.pullbtn').click(function(){
		  $('.pullbox').toggleClass('active');
	  });
	//-------- BODY TOGGLE JS END--------

  /*seller configuration js block started here*/
  $("#add_more_btn_conf").on("click",function( event ){
        var cloneData=$("#add_item_conf").clone(true);
        cloneData.find('input[type="text"]').val('');
        cloneData.find('.deleteItemDes').css('display','block');
        $(".add_more_col_conf").append(cloneData);
    //$("#add_item").clone(true).find('input[type="text"]').val('').appendTo(".add_more_col").append('<div><a href="#" class="deleteItemDes"><i class="fa fa-trash-o fa-lg"></i></a></div>');
  });

  $(document).on("click", "a.deleteItemDes", function( event ){

        event.preventDefault(); $(this).parent('div').parent().remove();

    });

  /*seller configuration js block ended here*/

  $("#roleForm").submit(function() {
        var formData = $("#roleForm").serialize();
        $.ajax({
            method: 'POST',
            url: '/seller/role/save',
            dataType: 'json',
            data: formData,
            success: function(msg) {
              var value = eval(msg);
              if(value.error == 'success'){
                window.location.href = '/seller/role';
                return false;
              }
                $.each(value, function(index, value) {
                    $("#" + index).html(value);
                });
            }
        });
    });


  $("#sellCheckAll").click(function(){
        $(".seller-per").prop('checked', $(this).prop("checked"));
    });
	/*js related code for seller user started here*/
	$("#supplierUserForm").submit(function() {
        var formData = $("#supplierUserForm").serialize();
        $.ajax({
            method: 'POST',
            url: '/seller/user/save',
            dataType: 'json',
            data: formData,
            success: function(msg) {
                var value = eval(msg);
                if(value.error == 'success'){
                    window.location.href = '/seller/user';
                    return false;
                }
                $.each(value, function(index, value) {
                    $("#" + index).html(value);
                });
            },
            beforeSend: function(){
                $('.loader').show();
            },
            complete: function(){
                $('.loader').hide();
            }
        });
    });
    /*js related code for seller user ended here*/

    /*Autocomplete Buyer List Start*/
    $("#buyer_name").autocomplete({
        source: '/seller/invoice/show',
        minLength: 0,
        width: 320,
        max: 10,
        select: function (event, ui) {
          //console.log(ui.item.payTerm)
            $("#buyer_id").val(ui.item.id);
            $("#in_due_date").val(ui.item.payTerm).attr('readonly','true').removeClass('datepicker');
          
        }
    }); 
    
    /*Autocomplete Buyer List End*/

    /*Autocomplete Associated PO List Start*/
    $('#po_no').autocomplete({
        source: function( request, response ) {
            var uuid=$("#buyer_id").val();
            
            $.ajax({
                url : '/seller/invoice/showPo',
                dataType: "json",
                data: {
                    term: request.term,
                    id:uuid
                },
                 success: function( data ) {
                  // response( data );
                  response( $.map( data, function( item ) {
                            var code = item.split("|");
                            return {
                                label: code[0],
                                value: code[0],
                                data : item
                            }
                        }));
                }
            });
        },
        minLength:0,
        width: 320,
        max: 10,
        select: function (event, ui) {
          
          var podata = ui.item.data.split("|");
          
          $("#po_id").val(podata[1]);
          $("#in_due_date").val(podata[2]).attr('readonly','true').removeClass('datepicker');

          if(podata[3].length>0){
            $("#currencyText").css('display','block');
            $("#currencyDrop").css('display','none');
            $("#curDrop").attr('name','cur_drop');
            $("#currency").val(podata[3]);
          }else{
            $("#currencyText").css('display','none');
            $("#currencyDrop").css('display','block');
            $("#curDrop").attr('name','currency');
          }

          $.ajax({
            url:'/seller/invoice/poItem',
            type:'get',
            data:{po_uuid:podata[1]},
            success:function(data){
                var html="";
                var json = $.parseJSON(data);
                var subtotal=0;
                for (var i=0;i<json.length;++i){
                   html+='<div class="form-group po_items">';
                   html+='<div class="col-sm-2"><input type="text" class="form-control ng-pristine ng-untouched ng-invalid ng-invalid-required" id="item_'+i+'" name="item[]" placeholder="Item" value="'+json[i].name+'"></div>';
                   html+='<div class="col-sm-2"><input type="text" class="form-control ng-pristine ng-untouched ng-invalid ng-invalid-required" id="description_'+i+'" name="description[]" placeholder="Description" value="'+json[i].description+'"></div>';
                   html+='<div class="col-sm-2"><input type="text" class="form-control ng-pristine ng-untouched ng-invalid ng-invalid-required quantity" id="quantity_'+i+'" name="quantity[]" placeholder="Quantity" value="'+json[i].quantity+'"></div>';
                   html+='<div class="col-sm-2"><input type="text" class="form-control ng-pristine ng-untouched ng-invalid ng-invalid-required price" id="price_'+i+'" name="price_per[]" placeholder="Price Per" value="'+json[i].unit_price+'"></div>';
                   html+='<div class="col-sm-2"><input type="text" class="form-control ng-pristine ng-untouched ng-invalid ng-invalid-required total" id="total_'+i+'" name="total[]" placeholder="Total" value="'+json[i].total+'" readonly></div>';
                   html+='<div class="col-sm-1">';
                   if(i>0){
                      html+='<a href="javascript:void(0);" id="item_'+(i)+'" style="color:#f00; font-size:20px;" class="remove-item"><i class="fa fa-trash-o fa-lg"></i></a>';
                    }
                   html+='</div></div>';
                   subtotal=subtotal+parseFloat(json[i].total);
                }
                
                $("#add_item").html(html);
                $("#subTotal").val(subtotal);
                $("#subTotalDis").val(subtotal);
                $("#finalAmount").val(subtotal);
                
            }
            
          });

        }       
      });
   /*Autocomplete Associated PO List End*/

   /* Add More Items Start*/
   $("#add_more_btn").click(function(){
    var count=$(".po_items").length;
    var html="";
    html+='<div class="form-group po_items">';
    html+='<div class="col-sm-2"><input type="text" class="form-control ng-pristine ng-untouched ng-invalid ng-invalid-required" id="item_'+count+'" name="item[]" placeholder="Item"></div>';
    html+='<div class="col-sm-2"><input type="text" class="form-control ng-pristine ng-untouched ng-invalid ng-invalid-required" id="description_'+count+'" name="description[]" placeholder="Description"></div>';
    html+='<div class="col-sm-2"><input type="text" class="form-control ng-pristine ng-untouched ng-invalid ng-invalid-required quantity" id="quantity_'+count+'" name="quantity[]" placeholder="Quantity"></div>';
    html+='<div class="col-sm-2"><input type="text" class="form-control ng-pristine ng-untouched ng-invalid ng-invalid-required price" id="priceper_'+count+'" name="price_per[]" placeholder="Price Per"></div>';
    html+='<div class="col-sm-2"><input type="text" class="form-control ng-pristine ng-untouched ng-invalid ng-invalid-required total" id="total_'+count+'" name="total[]" placeholder="Total" readonly></div>';
    html+='<div class="col-sm-1"><a href="javascript:void(0);" id="item_'+count+'" style="color:#f00; font-size:20px;" class="remove-item"><i class="fa fa-trash-o fa-lg"></i></a></div>';
    html+='</div>';
       $("#add_item").append(html);
    });
   /* Add More Items End*/

   
   /* Calculate Total Start*/
 $(document).on('focusout','.price',function(){
  if($(this).val().length>0){
      var subTotal=0; finalAmount=0; tax=0; discount=0;
      var price=parseFloat($(this).val());
      var qnty=$(this).parent().parent().find('div').find('.quantity').val();
      
      price = isNaN(price) ? '0' : price;
      price = isNaN(price) ? '0' : price;

      var total=price*qnty;
     
      $(this).parent().parent().find('div').find('.total').val(total);
      $(".total").each(function () {
        if($(this).val().length>0){
         subTotal = subTotal+parseFloat($(this).val());
        }
      });

      $('#subTotal').val(subTotal);
      
      discount = parseFloat($('#discount').val());
      discount = isNaN(discount) ? '0' : discount;

       if($('#discountType').val()=='1'){
         subTotal=subTotal-discount;
       }else{
         subTotal=subTotal-((subTotal*discount)/100).toFixed(2);
       }
      
        $("#subTotalDis").val(subTotal);

        finalAmount=getAllTax(subTotal);

        $("#finalAmount").val(finalAmount.toFixed(2));

   } 
 });
  /* Calculate Total End*/

  /* Calculate Discount Start*/
 $("#discount, #discountType").on('input', function(){ //calculate discount on focusout  of discount textbox
     
     if($('#discount').val().length>0){
      var finalAmount = 0;
      var disType = $('#discountType').val();
      
      var stotal = parseFloat($('#subTotal').val());
      var discount = parseFloat($('#discount').val());
      stotal = isNaN(stotal) ? '0' : stotal;
      discount = isNaN(discount) ? '0' : discount;

      if(disType=='1'){
        finalAmount = stotal-discount;
      }else{
        finalAmount = stotal-((stotal*discount)/100);
      }

      $("#subTotalDis").val(finalAmount.toFixed(2));
       
      finalAmount = getAllTax(finalAmount);

      $("#finalAmount").val(finalAmount.toFixed(2));
    }
 });

$(document).on('change','#discountType', function(){ //calculate discount on focusout  of discount textbox
     
     if($('#discount').val().length>0){
      var finalAmount = 0;
      var disType = $('#discountType').val();
      
      var stotal = parseFloat($('#subTotal').val());
      var discount = parseFloat($('#discount').val());
      stotal = isNaN(stotal) ? '0' : stotal;
      discount = isNaN(discount) ? '0' : discount;

      if(disType=='1'){
        finalAmount = stotal-discount;
      }else{
        finalAmount = stotal-((stotal*discount)/100);
      }

      $("#subTotalDis").val(finalAmount.toFixed(2));
       
      finalAmount = getAllTax(finalAmount);

      $("#finalAmount").val(finalAmount.toFixed(2));
    }
 });




  /* Calculate Discount End*/

  /* Calculate Tax Start*/

$(document).on('change','.tax-type',function(e1){ //calculate final amount using selected tax
 
    var tax = $(this);
   
    $('.tax-type').not(this).each(function() {
        if ($(this).val() == $(tax).val()) {
            alert("This Tax Already Taken!");
            $(tax).parent('div').parent().remove();
            //throw new Error("Something went badly wrong!");
        }
    });
  
      var taxValue=0;
      tax=$(tax).val().split(":");
      tax=parseFloat(tax[1]);
      tax = isNaN(tax) ? '0' : tax;

      var finalAmount=parseFloat($('#subTotalDis').val());
      $(this).parent().parent().find('div').find('.tax').val(tax);
      var allTax=0
      
      finalAmount = isNaN(finalAmount) ? '0' : finalAmount;
      
      taxValue=(finalAmount*tax)/100;
      $(this).parent().parent().find('div').find('.tax-val').val(taxValue.toFixed(2));
      
      $('.tax-val').each(function() {
         allTax=allTax+parseFloat($(this).val());
         
      });

      finalAmount=(finalAmount+allTax).toFixed(2);

      $("#finalAmount").val(finalAmount);
      
  });


$("#add_more_tax").on("click",function( event ){ //add more tax
      var label=$(".tax-row").length;
      var cloneData1 = $("#add_tax_row").clone(true);
      cloneData1.find('input[type="text"]').val('');
      cloneData1.find('.tax-label').html('Tax '+(label+1));
      cloneData1.find('.deleteTax').css('display','block');
      cloneData1.find('.addTax').css('display','none');
      $('.tax-row-container').append(cloneData1);
 });

$(document).on('click','.deleteTax',function(e){

    var taxAmt = 0, disAmt = 0, finalAmount = 0, label=1;;
    var prevTotal=parseFloat( $(this).parent().parent().find('div').find('.tax-val').val() );
    finalAmount=$("#finalAmount").val();
    finalAmount=finalAmount-prevTotal;
    $(this).parent('div').parent().remove();

    $("#finalAmount").val(finalAmount.toFixed(2));
    
    $(".tax-label").each(function(){
        $(this).html('Tax '+label);
     label++;
    })

 });
  /* Calculate Tax End*/

  /* Remove Item Start*/
$(document).on('click','.remove-item',function(e){
    var disAmt=0,finalAmount=0;
    var prev_total = parseFloat( $(this).parent().parent().find('div').find('.total').val() );
    var subTotal = parseFloat( $('#subTotal').val() );
    var tax = parseFloat( $('#tax').val() );
    var discount = parseFloat( $('#discount').val() );
  
       subTotal = subTotal-prev_total;
       $('#subTotal').val(subTotal);

       subTotal = parseFloat( $('#subTotal').val() );

       discount = isNaN(discount) ? '0' : discount;

       if($('#discountType').val()=='1'){
        subTotal = subTotal-discount;
       }else{
        subTotal = subTotal-((subTotal*discount)/100).toFixed(2);
       }
       

       $("#subTotalDis").val(subTotal);
       
       finalAmount = getAllTax(subTotal);

       $("#finalAmount").val(finalAmount);

     $(this).parent('div').parent().remove();

 });
/* Remove Item End*/

/* Delete Invoice Start*/
 $(document).on('click','.delete-invoice',function(){
      var id = $(this).attr('id');
      $("#confirmDelete").modal("show");
      $("#confirm").attr("href",id);
  });
/* Delete Invoice End*/

/* Show Invoice-Modal Start*/
 $(document).on('click','.invoice-modal',function(){
      var id = $(this).attr('id');

      $.ajax({
            url:'/seller/invoice/invoiceDetails/'+id,
            dataType:'html',
            success:function(data){
            
              $("#allModals").html(data);
              $("#myModalInvoice").modal("show");
              $("#invoiceAttach").filer({
                  extensions: ['jpg', 'jpeg', 'png', 'gif','doc','docx','xls','xlsx','pdf'],
                  changeInput: true,
                  showThumbs: true,
                  addMore: true,
                  captions: {
                      errors: {
                          filesLimit: "Only {{fi-limit}} files are allowed to be uploaded.",
                          filesType: "Only Images,Doc and PDF files are allowed to be uploaded.",
                          filesSize: "{{fi-name}} is too large! Please upload file up to {{fi-maxSize}} MB.",
                          filesSizeAll: "Files you've choosed are too large! Please upload files up to {{fi-maxSize}} MB."
                      }
                  }
                });

            }
          });
     
  });

}); 

$("#invoice_attach").filer({
    extensions: ['jpg', 'jpeg', 'png', 'gif','doc','docx','xls','xlsx','pdf'],
    changeInput: true,
    showThumbs: true,
    addMore: true,
    captions: {
                errors: {
                    filesLimit: "Only {{fi-limit}} files are allowed to be uploaded.",
                    filesType: "Only Images,Doc and PDF files are allowed to be uploaded.",
                    filesSize: "{{fi-name}} is too large! Please upload file up to {{fi-maxSize}} MB.",
                    filesSizeAll: "Files you've choosed are too large! Please upload files up to {{fi-maxSize}} MB."
                }
            }
  });

$(document).on('click',".delete-attach",function(){
  var id = $(this).attr('id');
   if(confirm("Are you sure you want to delete this?")){
    $.get('/seller/invoice/deleteAttachment/'+id, function(data, status){});
    $(this).parent().parent().parent().parent().parent().parent().parent().remove();
    }else{
        return false;
    }

}); 

//$("input[type=file]").removeAttr('multiple');

//Date Picker Js started
$( "#datepicker" ).datepicker({
    changeMonth: true,
    changeYear: true
});
$( "#datepicker1" ).datepicker({
    changeMonth: true,
    changeYear: true
});
$( "#datepicker2" ).datepicker({
    changeMonth: true,
    changeYear: true
});
$( "#datepicker3" ).datepicker({
    changeMonth: true,
    changeYear: true
});
$( ".datepicker" ).datepicker({
    changeMonth: true,
    changeYear: true,
});
//Date Picker Js End

/*seller po view block started here*/  
  $(".poView").on('click',function(e) {
        var poId = $(this).attr('data-id');
        var poUrl = '/seller/po/show/'+poId;
        $.ajax({
            method: 'get',
            url: poUrl,
            dataType: 'html',
            
            success: function(msg) {
              $('#poModalContainer').html(msg);
              $('#myModal_po').modal('show');
              $("#poAttach").filer({
                  extensions: ['jpg', 'jpeg', 'png', 'gif','doc','docx','xls','xlsx','pdf'],
                  changeInput: true,
                  showThumbs: true,
                  addMore: true,
                  captions: {
                      errors: {
                          filesLimit: "Only {{fi-limit}} files are allowed to be uploaded.",
                          filesType: "Only Images,Doc and PDF files are allowed to be uploaded.",
                          filesSize: "{{fi-name}} is too large! Please upload file up to {{fi-maxSize}} MB.",
                          filesSizeAll: "Files you've choosed are too large! Please upload files up to {{fi-maxSize}} MB."
                      }
                  }
                });
            },
            beforeSend: function(){
               // $('.loader').show();
            },
            complete: function(){
              //  $('.loader').hide();
            }
        });

    });
  /*seller po view block started here*/  

/* calculating all tax start*/
function getAllTax(finalAmount){
  var taxTotal = 0, taxValue = 0;
   $('.tax-type').each(function(e) {
        var t=$(this).val().split(":");
        
        t = parseFloat(t[1]);
        
        t = isNaN(t) ? '0' : t;
       
        taxValue=(finalAmount*t)/100;
        $(this).parent().parent().find('div').find('.tax-val').val(taxValue.toFixed(2));

        taxTotal=taxTotal+taxValue;
      });

      taxTotal = parseFloat(taxTotal);
      finalAmount=finalAmount+taxTotal;
      return(finalAmount);
}
/* calculating all tax end*/

/*show PI Modal Start*/

$(".pi-modal").click(function(){
      var id=$(this).attr('id');
      $.ajax({
            url:'/seller/piListing/showPiModal/'+id,
            dataType:'html',
            success:function(data){
             $("#piModal").html(data);
             $("#pi_Modal").modal("show");
             var d1=$("#paymentDate").val();
             var d2=$("#due_date").val();
             updateDate(d1);

             $( ".datepicker" ).datepicker({
                    changeMonth: true,
                    changeYear: true,
                    dateFormat:"dd M yy",
                    minDate: d1, 
                    maxDate: d2,
                    onSelect: function(selected,evnt) {
                           updateDate(selected);
                      },
                    //beforeShowDay: noWeekendsOrHolidays
                });
            }
          });
      
    });
$(window).load(function(){
  $('#eligibleAmtLabel').text($('#eligibleAmt').text());
});
/*show PI Modal End*/
var invNumOk= false,invoiceNo=$("#invoice_number"), invoiceInfo=$("#invoiceInfo");
$("#invoiceForm").submit(function(){
    if(!invNumOk){
     invoiceNo.focus();
      return false;
    }
  });

$("#invoice_number").blur(function(){
  var id=invoiceNo.val();
      $.ajax({
            url:'/seller/invoice/checkInvoiceNumber',
            type:'get',
            data:{invoice_number:id},
            success:function(data){
              console.log(data);
              if(data==1){
                invNumOk=true;
                invoiceInfo.html("");
              }else{
                invNumOk=false;
                invoiceInfo.html("<font color='red'>Already Exist</font>");
              }
             
             
           }
          });
      
    });

function updateDate(d){
  $(".earlyPayment").html(d);
  $("#paymentDate").val(d);
  var dueDate=$("#due_date").val();
  var minDays=$("#minDays").val();
  var maxDays=$("#maxDays").val();

  var oneDay = 24*60*60*1000; // hours*minutes*seconds*milliseconds
  var firstDate = new Date(d);
  var secondDate = new Date(dueDate);
   
  var diffDays = Math.round((secondDate.getTime()-firstDate.getTime())/(oneDay));
  if(minDays > diffDays || maxDays < diffDays){

    $("#submit").attr('disabled','true');

  }else{

    $("#submit").removeAttr('disabled');
    
  }
  
  $("#dayAccelerated").html(diffDays);
  $("#diffDays").val(diffDays);

}

/*code for I discounting Modal */
$(".iDisModal").click(function(e){
      var id = $(this).attr('data-id');
      console.log(id);
      $.ajax({
            url:'/seller/iDiscounting/iDisModal/'+id,
            dataType:'html',
            success:function(data){
             $("#iDisModalContainer").html(data);
             $("#iDisModal").modal("show");
            }
      });
});

/*code for I discounting Modal */
$("#requestPayment").click(function(e){
    alert('checked');
    $('.iDisCheck').each(function() {
      alert('checked');
      if(this.checked){
          var buyerId = $(this).closest("tr").find("input[name=buyerId]").val();
          var sellerId = $(this).closest("tr").find("input[name=sellerId]").val();
      }
    }
      //var id = $(this).attr('data-id');
      //console.log(id);
      e.preventDefault();
      $.ajax({
            url:'/seller/iDiscounting/requestPayment/iDisModal',
            dataType:'html',
            success:function(data){
             $("#iAppPayContainer").html(data);
             $("#iDescounting_remt").modal("show");
            }
      });
});

