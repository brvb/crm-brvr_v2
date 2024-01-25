<x-tenant-layout title="{{ __('Dashboard') }}" :themeAction="$themeAction">
    <div class="container-fluid">
        <div class="row">
            {{-- <div class="container-fluid"> --}}
                <!-- Add Order -->
                <div class="modal fade" id="addOrderModalside">
                  <div class="modal-dialog" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title">Add Event</h5>
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                        </button>
                      </div>
                      <div class="modal-body">
                        <form>
                          <div class="form-group">
                            <label class="text-black font-w500">Event Name</label>
                            <input type="text" class="form-control">
                          </div>
                          <div class="form-group">
                            <label class="text-black font-w500">Event Date</label>
                            <input type="date" class="form-control">
                          </div>
                          <div class="form-group">
                            <label class="text-black font-w500">Description</label>
                            <input type="text" class="form-control">
                          </div>
                          <div class="form-group">
                            <button type="button" class="btn btn-primary">Create</button>
                          </div>
                        </form>
                      </div>
                    </div>
                  </div>
                </div>
                
                <!-- row -->
                
                @livewire('tenant.dashboard.show')

                <div class="modal fade" id="modalInfo" data-id="" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                  <div class="modal-dialog" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Informação Tarefa</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body">
                       
                      </div>
                      <div class="modal-footer">
                        <!-- Fazer aqui   -->
          
                        @if(Auth::user()->type_user == "0")
                          <button type="button" id="deleteTaskButton" class="btn btn-danger">Apagar Tarefa</button>
                        @endif
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                      </div>
                    </div>
                  </div>
                </div>
                
              {{-- </div> --}}
        </div>
    </div>
</x-tenant-layout>

{{-- @push('custom-scripts') --}}
<script>
  var mesAtual = (new Date).getMonth() + 1;
  var anoAtual = (new Date).getFullYear();
  var date = (new Date);

 
  document.addEventListener('livewire:load', function () {
      restartCalendar();
          
  });

  
  
    jQuery('body').on('click','.fc-next-button',function(){
      
      var state = jQuery(".fc-state-active").text();
      Livewire.emit('CalendarNextChanges',date,state);

    });

    jQuery('body').on('click','.fc-prev-button',function(){

      var state = jQuery(".fc-state-active").text();
      Livewire.emit('CalendarPreviousChanges',date,state);

    });

    function restartCalendar()
    {
  
      var event = [];
      var startDate = "";
      var titleTask = "";
      var idTask = "";
      var descricao = [];
  
    jQuery('.events-tasks span').each(function(){
      if(jQuery(this).attr("data-previewdate") != "" && typeof jQuery(this).attr("data-previewdate") !== 'undefined')
      {
        titleTask = "ℹ "+jQuery(this).text();
        startDate = jQuery(this).attr("data-previewdate");
        totalHour = startDate+"T"+jQuery(this).attr("data-previewhour");
        color = jQuery(this).attr("data-color");
        idTask = jQuery(this).attr("data-id");
        descricao = jQuery(this).attr("data-obj");
      }
      if(jQuery(this).attr("data-scheduleddate") != "" && typeof jQuery(this).attr("data-scheduleddate") !== 'undefined') 
      {
        titleTask = jQuery(this).text();
        startDate = jQuery(this).attr("data-scheduleddate");
        totalHour = startDate+"T"+jQuery(this).attr("data-scheduledhour");
        color = jQuery(this).attr("data-color");
        idTask = jQuery(this).attr("data-id");
        descricao = jQuery(this).attr("data-obj");
      }
      
        {event.push({
            title: titleTask,
            start: totalHour,
            color: color,
            idTask: idTask,
            descricao: descricao
        })}

    });

      
    jQuery('#calendarr').fullCalendar({
      defaultView: 'listDay',
      events:event,
      allDay:true,
      header: {
             center:'title',
             left:'prev,next,today',
             right:'listMonth,listWeek,listDay'
         },
         buttonText: {
          today: 'Hoje',
          day: 'Dia',
          week:'Semana',
          month:'Mês'
        },
        slotLabelFormat: [
          'ddd D/M',
          'H:mm'
        ],
        allDayText: "Horas",
        monthNames: ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],
        monthNamesShort: ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'],
        dayNames: ['Domingo','Segunda','Terça','Quarta','Quinta','Sexta','Sábado'],
        dayNamesShort: ['Dom','Seg','Ter','Qua','Qui','Sex','Sáb'],
        timeFormat: 'HH(:mm)',
        aspectRatio:  2,
        eventRender: function (info,element) {
          element.find(".fc-list-item-time").attr("data-id",info.idTask);

          var object = JSON.parse(info.descricao);

          var view = jQuery('#calendarr').fullCalendar('getView');

          if(object != "[]" && view.name != 'listMonth')
          {
            jQuery.each( object, function( i, val ) {
              if(val.descricao == "" || val.descricao == null)
              {
                val.descricao = "<span style='color:green;'>Em aberto</span>";
              }
              element.find(".fc-list-item-title").append("<br><i class='fa-solid fa fa-arrow-right'></i> ["+val.date_begin+" "+val.hour_begin.slice(0,-3)+"] "+ val.descricao)
            });
          }

        } 
    });
    
    

  }

  
  var timer = 0;
  var delay = 200;
  var prevent = false;


  jQuery("body").on("click",".fc-list-item",function(){

    var cliente = jQuery(this).find('.fc-list-item-title a').text();
    var time = jQuery(this).find('.fc-list-item-time').text();
    var valueData = jQuery(this).find('.fc-list-item-time').attr('data-id');

    timer = setTimeout(function() {
      if (!prevent) {
        jQuery(".modal-body").empty();

        jQuery('#modalInfo').modal('show');
        jQuery(".modal-body").append("Cliente: "+cliente+ "<br>Hora Marcada: "+time); 

        

        jQuery("body").on("click","#deleteTaskButton",function(){

          window.location.href="deleteTask/"+valueData;
        });
      }
      prevent = false;
    }, delay);
  });

  jQuery("body").on("dblclick",".fc-list-item",function(){

    clearTimeout(timer);
    prevent = true;

    var valueData = jQuery(this).find('.fc-list-item-time').attr('data-id');

    Livewire.emit("checkReport",valueData);

    document.addEventListener('responseReport', function (e) {
      console.log(e.detail.response);
      
        if(e.detail.response == "existe")
        {
            window.location.href="tasks-reports/"+e.detail.value+"/edit";
        }
        else {
            window.location.href="tasks/"+e.detail.value+"/edit";
        }
    });
  });


    
  
    window.addEventListener('calendar',function(e){
      jQuery('#calendarr').fullCalendar('destroy');
      restartCalendar();
      jQuery('#calendarr').fullCalendar('gotoDate',e.detail.calendarResult);
      });

        
   
    
</script>   
{{-- @endpush --}}
