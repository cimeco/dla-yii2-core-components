var CharsCounter = new function(){
    this.init = function(){
        $('[data-chars-count] input').on('keydown change', function(){
            count($(this).closest('[data-chars-count]'));
        })
    }
    
    function count(element){
        var limit = $(element).attr('data-limit');
        var len = $(element).children('input').val().length;
        var $counter = $(element).children('[data-chars-counter]');

        var text = '';
        if(limit>0){
            text = len+'/'+limit;
        }else{
            text = len;
        }
        
        $counter.text(text);
        
        if(limit){
            if(len > 0.95*limit){
                $counter.removeClass('text-warning').addClass('text-danger');
            }else if(len > 0.75*limit){
                $counter.removeClass('text-danger').addClass('text-warning');
            }else{
                $counter.removeClass('text-warning text-danger');
            }
        }
    }
}