$(function () {
    'use strict';

    // Delete Confimation

    $('.confirm').click(function () {
        return confirm('هل تريد حذف هذا السجل؟');
    });

    $('#comp').on('change',function(){
        var companyID = $(this).val();
        if(companyID){
            $.ajax({
                type:'POST',
                url:'ajaxData.php',
                data:'company_id='+companyID,
                success:function(html){
                    $('#reef').html(html);
                    $('#unit').html('<option value="">اختر ريف أولا</option>'); 
                }
            }); 
        }else{
            $('#reef').html('<option value="">اختر شركة أولا</option>');
            $('#unit').html('<option value="">اختر ريف أولا</option>'); 
        }
        
    });
    
    $('#reef').on('change',function(){
        var reefID = $(this).val();
        if(reefID){
            $.ajax({
                type:'POST',
                url:'ajaxData.php',
                data:'reef_id='+reefID,
                success:function(html){
                    $('#unit').html(html);
                }
            }); 
        }else{
            $('#unit').html('<option value="">اختر ريف أولا</option>'); 
        }
    });

    $('#unit').on('change', function() {
        var unitID = $(this).val();
        if(unitID){
            $.ajax({
                type: 'POST',
                url: 'ajaxData.php',
                data: 'unit_id='+unitID,
                success:function(html) {
                    $('.space').html(html);
                }
            });        
        }else {
            $('.space').html('<option value="">اختر قطعة أولا</option>'); 
        }
    });
});


function filter() {
    var keyword = document.getElementById("search").value;
    var fleet = document.getElementById("select");
    for (var i = 0; i < fleet.length; i++) {
        var txt = fleet.options[i].text;
        if (txt.substring(0, keyword.length).toLowerCase() !== keyword.toLowerCase() && keyword.trim() !== "") {
            fleet.options[i].style.display = 'none';
        } else {
            fleet.options[i].style.display = 'list-item';
        }
    }
}