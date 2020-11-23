
      function getDataActivityLogSessionsForDataTables(requestedAliasUser,minDateRange,maxDateRange,action){
    var table = $('#dataTable').DataTable({
       "aaSorting": [[ 1, "asc" ]], // Sort by first column descending
        "bProcessing": true,
        "bDeferRender": true, 
        "bServerSide": true,
        "sAjaxSource": action+"?activityLogSessions=view&minDateRange="+minDateRange+"&maxDateRange="+maxDateRange+"&requestedAliasUser="+requestedAliasUser+"&nameDateFieldDB="+'bitacora_fecha', 
        "columnDefs": [ 
            {
                "targets": [ 0 ],
                "visible": false,
                "searchable": false                
            }
          ],'language': LANGUAGE_SPANISH_DATATABLES,
                "bDestroy": true,

    });
  
}


      function getDataCIE10CatalogForDataTables(url,idCapitulo){
    
    var table = $('#dataTable').DataTable({

        "bProcessing": true,
        "bDeferRender": true, 
        "bServerSide": true,
        "sAjaxSource": url+"?cie10listCatalog=view&idCapitulo="+idCapitulo, 
        "columnDefs": [
            ],'language': LANGUAGE_SPANISH_DATATABLES,
                "bDestroy": true,

    });
  
};

// devolvera datos para el select dinamico
function getCasesCIE10BySearchPattern(valueSearch,idCapituloCIE10,actionForAjax){

  $.ajax({
      type:'POST',
      url: actionForAjax,
      data:{'valueSearch': valueSearch,
          'idCapituloCIE10':idCapituloCIE10,'getCasesCIE10':true},
     success:function(casesCIE10){
      console.log(casesCIE10); 
      casesCIE10 = JSON.parse(casesCIE10);
      $('#catalogKeyCIE10').empty();
      casesCIE10.forEach(function(casesCIE10){
        $('#catalogKeyCIE10').append('<option value='+casesCIE10.catalog_key+'>'+casesCIE10.catalog_key + ' - ' + casesCIE10.nombre + '</option>')
        })                   
      }
    }); 
  }

// devolvera datos para el select dinamico
function getCasesCIE10ByidCapitulo(idCapituloCIE10,actionForAjax){
  $.ajax({
      type:'POST',
      url: actionForAjax,
      data:{'idCapituloCIE10':
      idCapituloCIE10,'getCasesCIE10':true,'searchByChapter':true},
      success:function(casesCIE10){
      casesCIE10 = JSON.parse(casesCIE10);
      $('#catalogKeyCIE10').empty();
      console.log(casesCIE10);
      casesCIE10.forEach(function(casesCIE10){
     $('#catalogKeyCIE10').append('<option value='+casesCIE10.catalog_key+'>'+casesCIE10.catalog_key + ' - ' + casesCIE10.nombre + '</option>')
        })
       }
     }); 
}

function getDataActivityLogCasosEpidemiForDataTables(requestedUser,minDateRange,maxDateRange,action){
    var table = $('#dataTable').DataTable({
       "aaSorting": [[ 1, "asc" ]], // Sort by first column descending
        "bProcessing": true,
        "bDeferRender": true, 
        "bServerSide": true,
        "sAjaxSource": action+"?activityLogCasosEpidemi=true&minDateRange="+minDateRange+"&maxDateRange="+maxDateRange+"&requestedUser="+requestedUser+"&nameDateFieldDB="+'bitacora_fecha',
     "aoColumnDefs": [      {
        // id_genero
      "targets": [ 0 ],
      "visible": false,
                "searchable": false                
      }]
        ,'language': LANGUAGE_SPANISH_DATATABLES,
                "bDestroy": true,
    });
}

function getDataCasosEpidemiForDataTables(requestedPersonEpidemi,minDateRange,maxDateRange,action){
    var table = $('#dataTable').DataTable({
//       "aaSorting": [[ 0, "asc" ]], // Sort by first column descending
        bProcessing: true,
        bDeferRender: true, 
        bServerSide: true,
        sAjaxSource: action+'?viewCasosEpidemi=true&minDateRange='+minDateRange+'&maxDateRange='+maxDateRange+'&requestedPersonEpidemi='+requestedPersonEpidemi+'&nameDateFieldDB='+'fecha_registro',
    aoColumnDefs: [

      {
        // id_genero
      targets: [ 2 ],
      visible: false,
                searchable: false                
      },

      {
        // id_nacionalidad_caso
      targets: [ 4 ],
      visible: false
      },

      {
        // doc_identidad_caso
      targets: [ 5 ],
      visible: false
      },

      {
        // comlumn clave_captitulo_cie10
      targets: [11],
      visible: false
      },

      {
        //id_parroquia
      targets: [ 15 ],
      visible: false,
                searchable: false                
      },     
  
      {
        //id_nacionalidad_usuario
      targets: [ 20 ],
      visible: false
      },
        
      {
        //doc_identidad_usuario
      targets: [ 21 ],
      visible: false
      },

      {
        //year_registro
      targets: [ 23 ],
      visible: false
      },

      {
        mData: null,
        sDefaultContent: '<button name= "delete" id= "delete" value="delete" class="btn btn-danger btn-circle btn-sm delete"><i class="fas fa-trash"></i> </button> <button value = "update" name = "update" id = "update" class="btn btn-info btn-circle btn-sm"><i class="fas fa-plus"></i></i></button>',

        aTargets: [26]
      }
        ],
        dom: 'lBfrtip',

      lengthMenu: [
            [ 10, 25, 50,100,200,500, -1 ],
            [ '10', '25', '50','100','200', '500', 'Todo' ]
        ],

        buttons: [
        {
       extend: 'excelHtml5',
        filename: 'Casos_Epidemiologicos_' + $('#minDateRange').val() + '_' + $('#maxDateRange').val() + ''
        },

        {
       extend: 'csvHtml5',
        filename: 'Casos_Epidemiologicos_' + $('#minDateRange').val() + '_' + $('#maxDateRange').val() + ''
        }
        ]
        ,
        
        language: LANGUAGE_SPANISH_DATATABLES,
                "bDestroy": true,

    });
  }

function getParroquias(actionForAjax){
  $.ajax({
      type:'POST',
      url: actionForAjax,
      data:{'getParroquias':true},
      success:function(dataJson){
      var parroquias = JSON.parse(dataJson);
      console.log(parroquias);
      parroquias.forEach(function(parroquias){
     $('#id_parroquia').append('<option value='+parroquias.id_parroquia+'>'+ parroquias.parroquia + '</option>')
        })
       }
     }); 
}


