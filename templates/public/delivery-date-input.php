<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>
<div class="wcdd-field-container">
    <label for="wcdd_input">Delivery Date :</label>
    <ul class="delivery_options">
        <li>
            <button  class="wcdd-button" data-date="<?php echo $available_dates[0]; ?>"><?php echo date("D d M",strtotime($available_dates[0])); ?></button>
        </li>
        <li>
            <button  class="wcdd-button" data-date="<?php echo $available_dates[1]; ?>"><?php echo date("D d M",strtotime($available_dates[1])); ?></button>
        </li>
        <li>
            <div class="wcdd-date-group">
                <button id="customDatebtn" class="wcdd-button">Custom Date</button>
                <input id="wcdd_input" type="text" name="wpwc_delivery_date" class="wcdd_delivery_date" readonly>
            </div>
        </li>
    </ul>


</div>
<script>
    (function($){
        $(document).ready(function(){

            /* select first date by default */
            $("#wcdd_input").val("<?php echo $available_dates[0] ?>");
            $(".delivery_options li:first-child button").addClass("active");
            //console.log($("#wcdd_input").val());
            /* Choose a date */

            $(".delivery_options li button").on("click",function(e){
                e.preventDefault();
                $(".delivery_options li button").removeClass("active");
                $(this).addClass("active");
                //$("#wcdd_input").val($(this).text());
                $("#wcdd_input").val($(this).data("date"));
                //console.log($("#wcdd_input").val());

            });

            var weekDaysToShow = <?php echo json_encode($show_on_days); ?>;

            //console.log(weekDaysToRemove);
            var shop_holidays = <?php echo json_encode($shop_holidays); ?>;
            /* add current day also to disabled dates */
            shop_holidays.push(jQuery.datepicker.formatDate('MM d, yy',new Date()));
            //console.log(shop_holidays);
            $("#wcdd_input").datepicker({
                minDate: 0,
                dateFormat: "MM d, yy",
                beforeShowDay: function(date) {
                    var day = date.getDay();

                    var string = jQuery.datepicker.formatDate('MM d, yy', date);
                    /* if all days selected */
                    if(weekDaysToShow.length == 7 & shop_holidays.indexOf(string) == -1 ){
                        return [date];
                    }
                    /* if partial days selected */
                    var has_day = 0;

                   // $.each(weekDaysToShow,function(index,value){
                        //console.log(day+ " "+ value);

                    if(weekDaysToShow.indexOf(day) != -1 & shop_holidays.indexOf(string) == -1 ){
                        has_day = 1;
                    }
                    //});
                    if(has_day) {
                        return [date];
                    }
                    else{
                        return false;
                    }
                    //return [(day != 1 && day != 2)];
                },
                onSelect: function(date,obj){
                    var fdate = jQuery.datepicker.formatDate('D d M',new Date(date));
                    $('#customDatebtn').text(fdate);
                    //console.log($("#wcdd_input").val());
                }
            });

            $('#customDatebtn').click(function(e) {
                e.preventDefault();
                //$(".delivery_options li button").removeClass("active");
                $('#wcdd_input').datepicker("show");
                /*setTimeout(function() {
                    $('#wcdd_input').datepicker("hide");
                }, 1000)*/
            })
        });
    })(jQuery)
</script>