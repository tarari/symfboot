var results_html=function(data){
    
     
        var str="";
        for(i=0;i<data.length;i++){
            str=str+'<p><strong><a href="'+data[i].url+'">'+data[i].title+'</a>\n\
            </strong> by '+data[i].author+' '+data[i].date+'</p>';
        }
        html='<div class="panel panel-default">'+
                '<div class="panel-body"><h1>Search results</h1>'+str+'</div>'+
                '</div>';
    
        return html;
    
};

$(document).ready(function(){
    
    $('.search-field').bind('blur',function(){
        var query=$('.search-field').val();
        if (query!==""){
            //ajax 
            $.ajax({
            url:URL+'search',
            data: {q: query},
            type: 'GET',
            success: function(response){
                
                $('#search-results').html(results_html(response));
                }
            });
        }else{
            $('#search-results').html();
        }
        });
        
    
 $('.search-field').bind('keypress',function(){
        var query=$('.search-field').val();
        if (query!==""){
            //ajax 
            $.ajax({
            url:URL+'search',
            data: {q: query},
            type: 'GET',
            success: function(response){
                
                $('#search-results').html(results_html(response));
                }
            });
        }else{
            $('#search-results').html();
        }
        });
        
    });
